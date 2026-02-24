<?php

namespace App\Http\Controllers;

use App\Models\ItemTransfer;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan histori transfer beserta relasi nama material dan proyek
        $transfers = ItemTransfer::all();
        
        // Memanggil data material dan proyek untuk mengisi Dropdown di Modal
        $materials = Material::orderBy('name', 'asc')->get();
        $projects = Project::orderBy('name', 'asc')->get();

        return view('itemtransfer.index', compact('transfers', 'materials', 'projects'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'material_id'   => 'required|integer|exists:materials,id',
            'to_project_id' => 'required|integer|exists:projects,id',
            'quantity'      => 'required|integer|min:1',
            'transfer_date' => 'required|date',
        ]);

        // 2. Ambil data material yang dipilih
        $material = Material::findOrFail($request->material_id);

        // 3. Cek apakah stok mencukupi
        if ($request->quantity > $material->stock) {
            return redirect()->back()
                ->withInput() // Mengembalikan isian form user sebelumnya
                ->withErrors(['quantity' => 'Stok tidak mencukupi! Sisa stok di gudang hanya ' . $material->stock . ' ' . $material->unit]);
        }

        // 4. Gunakan DB Transaction (Sangat penting untuk aplikasi inventaris!)
        DB::transaction(function () use ($request, $material) {
            
            // a. Buat riwayat pengiriman barang di tabel item_transfers
            ItemTransfer::create([
                'material_id'   => ucwords(strtolower($request->material_id)),
                'to_project_id' => ucwords(strtolower($request->to_project_id)),
                'quantity'      => $request->quantity,
                'transfer_date' => $request->transfer_date,
            ]);

            // b. Kurangi stok di tabel materials menggunakan fungsi bawaan Laravel (decrement)
            $material->decrement('stock', $request->quantity);
            
        });

        // 5. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Barang berhasil dikirim dan stok di gudang otomatis dikurangi!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(ItemTransfer $itemTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemTransfer $itemTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemTransfer $itemTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemTransfer $itemTransfer)
    {
        //
    }
}
