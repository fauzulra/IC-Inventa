<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Jika Anda ingin memanggil relasi supplier sekaligus: Material::with('supplier')->latest()->get()
        $materials = Material::all(); 
        $suppliers = Supplier::orderBy('name', 'asc')->get(); 
        // Kirimkan kedua variabel tersebut ke view
        return view('material.index', compact('materials', 'suppliers'));
    }

    // Fungsi untuk menyimpan data
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'required|string|max:50',
            // Jika tabel suppliers belum ada, hapus validasi 'exists:suppliers,id' di bawah ini sementara
            'supplier_id' => 'required|integer|exists:suppliers,id', 
        ]);

        // 2. Simpan ke Database
        Material::create([
            'name'        => ucwords(strtolower($request->name)), // Otomatis Huruf Kapital
            'unit'        => ucwords(strtolower($request->unit)), // Contoh: "pcs" jadi "Pcs"
            'stock'       => $request->stock,
            'supplier_id' => $request->supplier_id,
        ]);

        // 3. Redirect kembali
        return redirect()->back()->with('success', 'Data Material berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        //
    }
}
