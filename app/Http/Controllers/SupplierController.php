<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Description, phone, address dibuat nullable sesuai schema)
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'nullable|string',
        ]);

        // 2. Simpan ke Database
        Supplier::create([
            'name'        => ucwords(strtolower($request->name)), // Otomatis Huruf Kapital
            'description' => ucfirst($request->description),
            'phone'       => $request->phone,
            'address'     => ucwords(strtolower($request->address)),
        ]);

        // 3. Redirect kembali
        return redirect()->back()->with('success', 'Data Pemasok berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'nullable|string',
        ]);

        // 2. Cari Data di Database
        $supplier = Supplier::findOrFail($id);

        // 3. Lakukan Update Data
        $supplier->update([
            'name'        => ucwords(strtolower($request->name)),
            'description' => ucfirst($request->description),
            'phone'       => $request->phone,
            'address'     => ucwords(strtolower($request->address)),
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data Pemasok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // 1. Cari Data di Database
        $supplier = Supplier::findOrFail($id);

        // 2. Hapus Data
        $supplier->delete();

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data Pemasok berhasil dihapus!');
    }
}
