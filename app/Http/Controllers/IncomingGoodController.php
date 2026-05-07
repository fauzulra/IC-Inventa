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
    // public function index()
    // {
    //     $incomingGoods = IncomingGood::with(['material', 'supplier'])->latest()->get();
    //     $materials = Material::orderBy('name', 'asc')->get();
    //     $suppliers = Supplier::orderBy('name', 'asc')->get();

    //     return view('incominggood.index', compact('incomingGoods', 'materials', 'suppliers'));
    // }

    // // Fungsi untuk memproses data dari form
    // public function store(Request $request)
    // {
    //     // 1. Validasi Input
    //     $request->validate([
    //         'material_id'   => 'required|integer|exists:materials,id',
    //         'supplier_id'   => 'required|integer|exists:suppliers,id',
    //         'quantity'      => 'required|integer|min:1',
    //         'date_received' => 'required|date',
    //     ]);

    //     // 2. Simpan riwayat barang masuk ke tabel incoming_goods
    //     IncomingGood::create([
    //         'material_id'   => $request->material_id,
    //         'supplier_id'   => $request->supplier_id,
    //         'quantity'      => $request->quantity,
    //         'date_received' => $request->date_received,
    //     ]);

    //     // 3. Tambahkan stok ke tabel materials secara otomatis
    //     // Fungsi increment() otomatis menambah stok sesuai quantity yang diinput
    //     $material = Material::findOrFail($request->material_id);
    //     $material->increment('stock', $request->quantity);

    //     // 4. Selesai dan kembali
    //     return redirect()->back()->with('success', 'Barang masuk berhasil dicatat dan stok gudang otomatis bertambah!');
    // }

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

        return view('incominggood.index', compact('project', 'incomingGoods', 'materials', 'suppliers', 'approvedOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'    => 'required|integer|exists:projects,id',
            'order_id'      => 'nullable|integer|exists:orders,id', // Tambahan: ID pesanan
            'material_id'   => 'required|integer|exists:materials,id',
            'supplier_id'   => 'nullable|integer|exists:suppliers,id',
            'quantity'      => 'required|integer|min:1',
            'date_received' => 'required|date',
        ]);

        // 1. Catat ke tabel incoming_goods
        IncomingGood::create([
            'project_id'    => $request->project_id,
            'material_id'   => $request->material_id,
            'supplier_id'   => $request->supplier_id,
            'quantity'      => $request->quantity,
            'date_received' => $request->date_received,
        ]);

        // 2. Tambah stok di Pivot Table Proyek tersebut
        $project = Project::find($request->project_id);
        $materialInProject = $project->materials()->where('material_id', $request->material_id)->first();

        if ($materialInProject) {
            $newStock = $materialInProject->pivot->stock + $request->quantity;
            $project->materials()->updateExistingPivot($request->material_id, ['stock' => $newStock]);
        } else {
            $project->materials()->attach($request->material_id, ['stock' => $request->quantity]);
        }

        // 3. LOGIKA BARU: Jika ini berasal dari pesanan (Order), ubah statusnya agar selesai!
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            if ($order) {
                $order->update(['status' => 'Diterima Gudang']);
            }
        }

        return redirect()->back()->with('success', 'Barang diterima! Stok proyek bertambah dan Pesanan telah ditutup.');
    }

}
