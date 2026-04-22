<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    public function index()
    {
        // Mengambil semua data order beserta relasi proyeknya, diurutkan dari yang terbaru
        $orders = Order::with('project')->orderBy('created_at', 'desc')->get();
        
        return view('confirmation.index', compact('orders'));
    }

    // Fungsi untuk memproses update status dari Modal
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}