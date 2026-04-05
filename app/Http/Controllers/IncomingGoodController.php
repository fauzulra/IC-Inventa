<?php

namespace App\Http\Controllers;

use App\Models\IncomingGood;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;

class IncomingGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomingGoods = IncomingGood::with(['material', 'supplier'])->latest()->get();
        $materials = Material::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return view('incominggood.index', compact('incomingGoods', 'materials', 'suppliers'));
    }

    // Fungsi untuk memproses data dari form
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'material_id'   => 'required|integer|exists:materials,id',
            'supplier_id'   => 'required|integer|exists:suppliers,id',
            'quantity'      => 'required|integer|min:1',
            'date_received' => 'required|date',
        ]);

        // 2. Simpan riwayat barang masuk ke tabel incoming_goods
        IncomingGood::create([
            'material_id'   => $request->material_id,
            'supplier_id'   => $request->supplier_id,
            'quantity'      => $request->quantity,
            'date_received' => $request->date_received,
        ]);

        // 3. Tambahkan stok ke tabel materials secara otomatis
        // Fungsi increment() otomatis menambah stok sesuai quantity yang diinput
        $material = Material::findOrFail($request->material_id);
        $material->increment('stock', $request->quantity);

        // 4. Selesai dan kembali
        return redirect()->back()->with('success', 'Barang masuk berhasil dicatat dan stok gudang otomatis bertambah!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(IncomingGood $incomingGood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingGood $incomingGood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingGood $incomingGood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingGood $incomingGood)
    {
        //
    }
}
