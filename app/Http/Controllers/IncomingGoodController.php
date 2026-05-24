<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\Material;
use App\Models\Order;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;

class IncomingGoodController extends Controller
{


    public function index()
    {
        $user = auth()->user();
        
        // JIKA BUKAN ADMIN: Langsung lempar ke tabel barang masuk proyeknya
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('incominggood.project.show', $user->project_id);
        }

        // JIKA ADMIN: Tampilkan form pilih proyek
        $projects = Project::paginate(5);
        $title = "Pilih Proyek - Barang Masuk";
        
        // KUNCI DINAMISNYA ADA DI SINI:
        // Kita arahkan agar setelah proyek diklik, perginya ke route 'incominggood.project.show'
        $targetRoute = 'incominggood.project.show'; 
        
        // Kita panggil/pinjam file view select_project milik modul material
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

    // =========================================================================
    // TAMPILKAN TABEL BARANG MASUK SETELAH PROYEK DIPILIH
    // =========================================================================
    public function showProjectIncoming($id)
    {
        $project = Project::findOrFail($id);
        
        $incomingGoods = IncomingGood::with(['material', 'supplier'])
                                     ->where('project_id', $id)
                                     ->latest()
                                     ->get();

        // LOGIKA BARU: Ambil material HANYA yang terdaftar di proyek ini saja!
        $materials = $project->materials()->orderBy('name', 'asc')->get(); 

        $suppliers = Supplier::orderBy('name', 'asc')->get();

        $approvedOrders = Order::where('project_id', $id)
                                           ->where('status', 'Diterima') 
                                           ->get();

        $allProjects = auth()->user()->hasRole('admin') ? Project::orderBy('name', 'asc')->get() : [];

        return view('incominggood.index', compact('project', 'incomingGoods', 'materials', 'suppliers', 'approvedOrders', 'allProjects'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'project_id'    => 'required|integer|exists:projects,id',
            'order_id'      => 'required|integer|exists:orders,id', // Diubah menjadi required karena ini wajib dari pesanan
            'quantity'      => 'required|integer|min:1',
            'date_received' => 'required|date',
            'supplier_id'   => 'nullable|integer|exists:suppliers,id',
        ]);

        $project = Project::find($request->project_id);
        $order = Order::find($request->order_id);

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan!');
        }

        // =========================================================================
        // LOGIKA AUTO-CREATE MATERIAL
        // =========================================================================
        
        // 1. Cek apakah di tabel 'materials' (Global) sudah ada material dengan NAMA dan SATUAN yang persis sama
        // Menggunakan huruf kecil (strtolower) agar pencariannya tidak sensitif huruf besar/kecil
        $material = Material::whereRaw('LOWER(name) = ?', [strtolower($order->name)])
                            ->whereRaw('LOWER(unit) = ?', [strtolower($order->unit)])
                            ->first();

        // 2. Jika material BELUM ADA di database global, sistem buatkan yang baru
        if (!$material) {
            $material = Material::create([
                'name'        => ucwords(strtolower($order->name)),
                'unit'        => ucwords(strtolower($order->unit)),
                'supplier_id' => $request->supplier_id, // Bisa null
            ]);
        }

        // =========================================================================
        // LOGIKA PENAMBAHAN STOK DI PIVOT TABLE PROYEK
        // =========================================================================
        
        // Cek apakah proyek ini sudah "memiliki" material tersebut di Pivot Table
        $materialInProject = $project->materials()->where('material_id', $material->id)->first();

        if ($materialInProject) {
            // Jika sudah ada di proyek, tambahkan stoknya
            $newStock = $materialInProject->pivot->stock + $request->quantity;
            $project->materials()->updateExistingPivot($material->id, ['stock' => $newStock]);
        } else {
            // Jika belum ada di proyek, pasangkan (attach) dengan stok awal dari penerimaan ini
            $project->materials()->attach($material->id, ['stock' => $request->quantity]);
        }

        // =========================================================================
        // PENCATATAN HISTORI BARANG MASUK & UPDATE STATUS PESANAN
        // =========================================================================
        
        // Catat ke tabel riwayat barang masuk (incoming_goods)
        IncomingGood::create([
            'project_id'    => $request->project_id,
            'material_id'   => $material->id, // Menggunakan ID dari material yang ditemukan/baru dibuat
            'supplier_id'   => $request->supplier_id,
            'quantity'      => $request->quantity,
            'date_received' => $request->date_received,
        ]);

        // Ubah status pesanan agar ditutup
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            if ($order) {
                $order->update(['status' => 'Diterima Gudang']);
            }
        }

        // Redirect kembali dengan menambahkan 'reopen_modal' agar View tahu modal harus dibuka lagi
        return redirect()->back()
            ->with('success', 'Barang berhasil diterima!.')
            ->with('reopen_modal', true);
    }

    public function printReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();
        
        // Tentukan ID proyek yang akan dicetak
        // Jika Admin, ambil dari dropdown form. Jika Staff/Logistik, paksa gunakan project_id mereka.
        $projectId = $user->hasRole('admin') ? $request->project_id : $user->project_id;

        // Mulai Query
        $query = IncomingGood::with(['material', 'supplier', 'project'])
                             ->whereBetween('date_received', [$request->start_date, $request->end_date]);

        // Filter berdasarkan Proyek (Jika admin memilih 'all', lewati filter ini)
        if ($projectId && $projectId != 'all') {
            $query->where('project_id', $projectId);
            $projectName = Project::find($projectId)->name;
        } else {
            $projectName = 'Semua Proyek';
        }

        // Ambil data yang sudah difilter
        $incomingGoods = $query->orderBy('date_received', 'asc')->get();

        // Tampilkan ke halaman khusus cetak (kertas)
        return view('incominggood.print', compact('incomingGoods', 'projectName', 'request'));
    }

}
