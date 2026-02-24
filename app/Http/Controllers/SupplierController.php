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
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
