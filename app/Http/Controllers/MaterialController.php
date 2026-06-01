<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    // =========================================================================
    // BAGIAN 1: DATA MASTER MATERIAL
    // =========================================================================
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->project_id && !$request->has('browse')) {
            return redirect()->route('material.project.show', $user->project_id);
        }

        if (auth()->user()->hasRole('admin')) {
            $projects = Project::paginate(5);
        } else {
            $projects = Project::where('id', '!=', auth()->user()->project_id)->paginate(5);
        }

        $title = "Daftar Material Proyek";
        $targetRoute = 'material.project.show'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

    public function showProjectMaterials($id)
    {
        $project = Project::findOrFail($id);
        
        $materials = $project->materials; 
        $suppliers = \App\Models\Supplier::all();
        
        return view('material.project_materials', compact('project', 'materials', 'suppliers'));
    }

    // =========================================================================
    // BAGIAN 2: PEMESANAN (ORDER) MATERIAL
    // =========================================================================
    public function orderIndex()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('material.order.show', $user->project_id);
        }

        $projects = Project::paginate(5);
        $title = "Pemesanan Material Proyek";
        $targetRoute = 'material.order.show'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

   public function showProjectOrders($id)
    {
        $project = Project::findOrFail($id);
        $orders = Order::with('user')->where('project_id', $id)->orderBy('created_at', 'desc')->get();
        $users = User::all();
        
        // TAMBAHKAN INI: Ambil data nama dan satuan material (tanpa duplikat)
        $materials = \App\Models\Material::select('name', 'unit')->distinct()->get();
        
        return view('material.order', compact('project', 'orders', 'users', 'materials'));
    }

    // =========================================================================
    // BAGIAN 3: KONFIRMASI MATERIAL
    // =========================================================================
    public function confirmationIndex()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('material.confirmation.show', $user->project_id);
        }

        $projects = Project::paginate(5);
        $title = "Konfirmasi Material Proyek";
        $targetRoute = 'material.confirmation.show'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

    public function showProjectConfirmations($id)
    {
        $project = Project::findOrFail($id);
        $orders = Order::where('project_id', $id)->orderBy('created_at', 'desc')->get();
        
        $users = \App\Models\User::all();
        
        return view('material.confirmation', compact('project', 'orders', 'users'));
    }

    public function orderStore(Request $request)
    {
        // Validasi input diubah menggunakan tanda bintang (*) karena formatnya array
        $request->validate([
            'project_id'       => 'required|integer|exists:projects,id',
            'user_id'          => 'required|integer|exists:users,id',
            'name.*'           => 'required|string|max:255',
            'quantity.*'       => 'required|integer|min:1',
            'unit.*'           => 'required|string|max:50',
            'request_date.*'   => 'required|date',
            'keterangan.*'     => 'nullable|string|max:255', // Validasi keterangan
        ]);

        // Looping semua pesanan yang dimasukkan (karena bisa tambah banyak baris sekaligus)
        foreach ($request->name as $index => $materialName) {
            Order::create([
                'project_id'   => $request->project_id,
                'user_id'      => $request->user_id,
                'name'         => $materialName,
                'quantity'     => $request->quantity[$index],
                'unit'         => $request->unit[$index],
                'request_date' => $request->request_date[$index],
                'keterangan'   => $request->keterangan[$index] ?? null, // Simpan keterangan
                'status'       => 'pending'
            ]);
        }

        return redirect()->back()->with('success', 'Semua pesanan material berhasil ditambahkan!');
    }

    public function orderUpdate(Request $request, $id)
    {
        // Perhatikan ini BUKAN array (tidak pakai bintang *) karena diedit satu per satu
        $request->validate([
            'name'         => 'required|string|max:255',
            'quantity'     => 'required|integer|min:1',
            'unit'         => 'required|string|max:50',
            'request_date' => 'required|date',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        if (strtolower($order->status) !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pesanan berstatus pending yang bisa diedit.');
        }

        $order->update([
            'name'         => $request->name,
            'quantity'     => $request->quantity,
            'unit'         => $request->unit,
            'request_date' => $request->request_date,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data pesanan berhasil diperbarui!');
    }

    public function orderDestroy($id)
    {
        $order = Order::findOrFail($id);

        if (strtolower($order->status) !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan yang sudah diproses tidak bisa dibatalkan.');
        }

        $order->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan/dihapus!');
    }

    public function confirmationUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan material berhasil diperbarui!');
    }

    // ==========================================
    // FUNGSI CRUD MATERIAL & PIVOT
    // ==========================================

    public function store(Request $request)
    {
        $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'name'        => 'required|string|max:255',
            'unit'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:0',
            'keterangan'  => 'nullable|string|max:255', // <-- Tambahan validasi
        ]);

        $material = Material::create([
            'name'        => $request->name,
            'unit'        => $request->unit,
            'supplier_id' => $request->supplier_id,
            'keterangan'  => $request->keterangan, // <-- Simpan ke database
        ]);

        $project = Project::find($request->project_id);
        $project->materials()->attach($material->id, ['stock' => $request->stock]);

        return redirect()->back()->with('success', 'Data material berhasil ditambahkan ke proyek!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'stock'       => 'required|integer|min:0',
        ]);

        $material = Material::findOrFail($id);

        $material->update([
            'name'        => $request->name,
            'unit'        => $request->unit,
            'supplier_id' => $request->supplier_id,
        ]);

        $project = Project::find($request->project_id);
        $project->materials()->updateExistingPivot($material->id, ['stock' => $request->stock]);

        return redirect()->back()->with('success', 'Data material dan stok berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $project = Project::find($request->project_id);
        $project->materials()->detach($material->id);

        return redirect()->back()->with('success', 'Material berhasil dikeluarkan dari proyek!');
    }      
}