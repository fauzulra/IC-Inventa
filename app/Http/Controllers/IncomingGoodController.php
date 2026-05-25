<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\ItemTransfer; // PASTIKAN IMPORT INI ADA
use App\Models\Material;
use App\Models\Order;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;

class IncomingGoodController extends Controller
{
    // ... (Fungsi index() tetap sama) ...

    public function showProjectIncoming($id)
    {
        $project = Project::findOrFail($id);
        
        $incomingGoods = IncomingGood::with(['material', 'supplier'])
                                     ->where('project_id', $id)
                                     ->latest()
                                     ->get();

        $materials = $project->materials()->orderBy('name', 'asc')->get(); 
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        // 1. Ambil data pesanan ke supplier yang sudah dikonfirmasi
        $approvedOrders = Order::where('project_id', $id)
                               ->where('status', 'Diterima') 
                               ->get();

        // 2. Ambil data transfer antar proyek yang ditujukan ke proyek ini dan sudah dikirim/diterima
        $approvedTransfers = ItemTransfer::with(['material', 'fromProject'])
                                         ->where('to_project_id', $id)
                                         ->where('status', 'Diterima') 
                                         ->get();

        $allProjects = auth()->user()->hasRole('admin') ? Project::orderBy('name', 'asc')->get() : [];

        return view('incominggood.index', compact('project', 'incomingGoods', 'materials', 'suppliers', 'approvedOrders', 'approvedTransfers', 'allProjects'));
    }

    public function store(Request $request)
    {
        // Validasi input diubah dari order_id menjadi reference_id
        $request->validate([
            'project_id'    => 'required|integer|exists:projects,id',
            'reference_id'  => 'required|string', // Menampung gabungan tipe dan ID (ex: "order_1" atau "transfer_2")
            'quantity'      => 'required|integer|min:1',
            'date_received' => 'required|date',
            'supplier_id'   => 'nullable|integer|exists:suppliers,id',
        ]);

        $project = Project::findOrFail($request->project_id);

        // Pecah reference_id untuk mengetahui apakah ini dari Order atau ItemTransfer
        $ref = explode('_', $request->reference_id);
        $type = $ref[0]; // 'order' atau 'transfer'
        $id = $ref[1];   // ID datanya

        $material = null;

        // =========================================================================
        // LOGIKA PENENTUAN MATERIAL & UPDATE STATUS SUMBER
        // =========================================================================
        if ($type === 'order') {
            $order = Order::findOrFail($id);
            
            // Cek/Buat Master Material jika dari Order
            $material = Material::whereRaw('LOWER(name) = ?', [strtolower($order->name)])
                                ->whereRaw('LOWER(unit) = ?', [strtolower($order->unit)])
                                ->first();

            if (!$material) {
                $material = Material::create([
                    'name'        => ucwords(strtolower($order->name)),
                    'unit'        => ucwords(strtolower($order->unit)),
                    'supplier_id' => $request->supplier_id,
                ]);
            }
            
            // Tutup pesanan
            $order->update(['status' => 'Diterima Gudang']);

        } elseif ($type === 'transfer') {
            $transfer = ItemTransfer::findOrFail($id);
            
            // Jika dari transfer, material pasti sudah ada di database global
            $material = Material::findOrFail($transfer->material_id);
            
            // Tutup status transfer
            $transfer->update(['status' => 'Selesai']); 
        }

        if (!$material) {
            return redirect()->back()->with('error', 'Gagal memproses data material.');
        }

        // =========================================================================
        // LOGIKA PENAMBAHAN STOK DI PIVOT TABLE PROYEK
        // =========================================================================
        $materialInProject = $project->materials()->where('material_id', $material->id)->first();

        if ($materialInProject) {
            $newStock = $materialInProject->pivot->stock + $request->quantity;
            $project->materials()->updateExistingPivot($material->id, ['stock' => $newStock]);
        } else {
            $project->materials()->attach($material->id, ['stock' => $request->quantity]);
        }

        // =========================================================================
        // PENCATATAN HISTORI BARANG MASUK
        // =========================================================================
        IncomingGood::create([
            'project_id'    => $request->project_id,
            'material_id'   => $material->id,
            'supplier_id'   => $type === 'order' ? $request->supplier_id : null, // Supplier kosong jika dari transfer
            'quantity'      => $request->quantity,
            'date_received' => $request->date_received,
        ]);

        return redirect()->back()
            ->with('success', 'Barang berhasil diterima! Stok telah ditambahkan.')
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
