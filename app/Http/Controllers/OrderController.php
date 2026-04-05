<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        
        // Memanggil data untuk mengisi Dropdown di Modal
        $materials = Material::orderBy('name', 'asc')->get();
        $projects = Project::orderBy('name', 'asc')->get();
        $users = User::orderBy('name', 'asc')->get();

        return view('order.index', compact('orders', 'materials', 'projects', 'users'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'material_id'  => 'required|integer|exists:materials,id',
            'quantity'     => 'required|integer|min:1',
            'request_date' => 'required|date',
            'user_id'      => 'required|integer|exists:users,id',
            'project_id'   => 'required|integer|exists:projects,id',
        ]);

        // 2. Simpan pesanan ke database
        Order::create([
            'material_id'  => $request->material_id,
            'quantity'     => $request->quantity,
            'request_date' => $request->request_date,
            'user_id'      => $request->user_id,
            'project_id'   => $request->project_id,
            // 'status' tidak perlu diisi, database akan mengisinya otomatis dengan 'pending'
        ]);

        // 3. Redirect kembali
        return redirect()->back()->with('success', 'Pesanan berhasil diajukan dan menunggu persetujuan!');
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
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
