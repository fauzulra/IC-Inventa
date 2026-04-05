<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\OutgoingGood;
use App\Models\Project;
use Illuminate\Http\Request;

class OutgoingGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $outgoingGoods = OutgoingGood::with(['material', 'project'])->latest()->get();
    
    // Variabel untuk mengisi dropdown form
    $materials = Material::orderBy('name', 'asc')->get();
    $projects = Project::orderBy('name', 'asc')->get();

    return view('outgoinggood.index', compact('outgoingGoods', 'materials', 'projects'));
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
        // 1. Validasi Input
        $request->validate([
            'material_id'  => 'required|integer|exists:materials,id',
            'quantity'     => 'required|integer|min:1',
            'date_shipped' => 'required|date',
            'project_id'   => 'required|integer|exists:projects,id',
        ]);

        // 2. Ambil data material dari database berdasarkan ID yang dipilih
        $material = Material::findOrFail($request->material_id);

        // 3. Pengecekan Stok (Validasi agar tidak minus)
        if ($request->quantity > $material->stock) {
            return redirect()->back()
                ->withInput() // Mengembalikan isian form agar tidak perlu ketik ulang
                ->withErrors(['quantity' => 'Stok tidak mencukupi! Sisa stok saat ini hanya ' . $material->stock . ' ' . $material->unit]);
        }

        // 4. Simpan riwayat pengeluaran barang ke tabel outgoing_goods
        OutgoingGood::create([
            'material_id'  => $request->material_id,
            'quantity'     => $request->quantity,
            'date_shipped' => $request->date_shipped,
            'project_id'   => $request->project_id,
        ]);

        // 5. Kurangi stok di tabel materials secara otomatis
        $material->decrement('stock', $request->quantity);

        // 6. Selesai dan kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Barang keluar berhasil dicatat dan stok otomatis berkurang!');
    }

    /**
     * Display the specified resource.
     */
    public function show(OutgoingGood $outgoingGood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingGood $outgoingGood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingGood $outgoingGood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingGood $outgoingGood)
    {
        //
    }
}
