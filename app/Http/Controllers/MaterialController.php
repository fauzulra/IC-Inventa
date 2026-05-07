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
    public function index()
    {
        $user = auth()->user();
        
        // MENGGUNAKAN SPATIE: Jika BUKAN admin, lempar ke proyeknya
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('material.project.show', $user->project_id);
        }

        // JIKA ADMIN: Tampilkan form pilih proyek
        $projects = Project::paginate(5);
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
        
        // MENGGUNAKAN SPATIE: Jika BUKAN admin, lempar ke proyeknya
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('material.order.show', $user->project_id);
        }

        // JIKA ADMIN: Tampilkan form pilih proyek
        $projects = Project::paginate(5);
        $title = "Pemesanan Material Proyek";
        $targetRoute = 'material.order.show'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

   public function showProjectOrders($id)
    {
        $project = Project::findOrFail($id);
        $orders = Order::with('user')->where('project_id', $id)->orderBy('created_at', 'desc')->get();
        
        // TAMBAHKAN INI UNTUK DROPDOWN USERS:
        $users = User::all();
        
        return view('material.order', compact('project', 'orders', 'users'));
    }

    // =========================================================================
    // BAGIAN 3: KONFIRMASI MATERIAL
    // =========================================================================
    public function confirmationIndex()
    {
        $user = auth()->user();
        
        // MENGGUNAKAN SPATIE: Jika BUKAN admin, lempar ke proyeknya
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('material.confirmation.show', $user->project_id);
        }

        // JIKA ADMIN: Tampilkan form pilih proyek
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



    // public function index()
    // {
    //     // Jika Anda ingin memanggil relasi supplier sekaligus: Material::with('supplier')->latest()->get()
    //     $materials = Material::all(); 
    //     $suppliers = Supplier::orderBy('name', 'asc')->get(); 
    //     // Kirimkan kedua variabel tersebut ke view
    //     return view('material.index', compact('materials', 'suppliers'));
    // }

    // public function index()
    // {
    //     // Gunakan paginate(4) atau sesuai kebutuhan agar mirip desain Figma Anda
    //     $projects = Project::paginate(5); 
    //     return view('material.index', compact('projects'));
    // }

    // public function showProjectMaterials($id)
    // {
    //     // Cari proyeknya
    //     $project = Project::findOrFail($id);
        
    //     // Ambil material yang HANYA dimiliki oleh proyek ini beserta data stok di pivotnya
    //     $materials = $project->materials; 
        
    //     // Kita butuh data supplier untuk form tambah (jika masih digunakan)
    //     $suppliers = \App\Models\Supplier::all();

    //     // Arahkan ke view tabel Anda (Anda bisa me-rename file view lama menjadi project_materials.blade.php)
    //     return view('material.project_materials', compact('project', 'materials', 'suppliers'));
    // }

    // public function orderIndex()
    // {
    //     // Mengambil semua data order beserta relasi untuk ditampilkan di tabel
    //     $orders = Order::with(['project', 'user'])->orderBy('created_at', 'desc')->get();
        
    //     // Data ini dikirim untuk mengisi pilihan (dropdown) di dalam form tambah pesanan
    //     $projects = Project::orderBy('name', 'asc')->get();
    //     $users = User::orderBy('name', 'asc')->get();

    //     return view('material.order', compact('orders', 'projects', 'users'));
    // }

    public function orderStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'quantity'     => 'required|integer|min:1',
            'unit'         => 'required|string|max:50',
            'request_date' => 'required|date',
            'user_id'      => 'required|integer|exists:users,id',
            'project_id'   => 'required|integer|exists:projects,id',
        ]);

        // Simpan pesanan dan set status default menjadi 'pending'
        Order::create(array_merge($request->all(), ['status' => 'pending']));

        return redirect()->back()->with('success', 'Pesanan material berhasil diajukan!');
    }


    // // =========================================================================
    // // BAGIAN 3: KONFIRMASI MATERIAL
    // // =========================================================================
    // public function confirmationIndex()
    // {
    //     // Menampilkan tabel untuk update status
    //     $orders = Order::with('project')->orderBy('created_at', 'desc')->get();
    //     return view('material.confirmation', compact('orders'));
    // }

    public function confirmationUpdate(Request $request, $id)
    {
        // Validasi status yang dikirim dari form/modal SweetAlert
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan material berhasil diperbarui!');
    }


    // // Fungsi untuk menyimpan data
    // // public function store(Request $request)
    // // {
    // //     // 1. Validasi Input
    // //     $request->validate([
    // //         'name'        => 'required|string|max:255',
    // //         'stock'       => 'required|integer|min:0',
    // //         'unit'        => 'required|string|max:50',
    // //         // Jika tabel suppliers belum ada, hapus validasi 'exists:suppliers,id' di bawah ini sementara
    // //         'supplier_id' => 'required|integer|exists:suppliers,id', 
    // //     ]);

    // //     // 2. Simpan ke Database
    // //     Material::create([
    // //         'name'        => ucwords(strtolower($request->name)), // Otomatis Huruf Kapital
    // //         'unit'        => ucwords(strtolower($request->unit)), // Contoh: "pcs" jadi "Pcs"
    // //         'stock'       => $request->stock,
    // //         'supplier_id' => $request->supplier_id,
    // //     ]);

    // //     // 3. Redirect kembali
    // //     return redirect()->back()->with('success', 'Data Material berhasil ditambahkan!');
    // // }
    // // public function update(Request $request, $id)
    // // {
    // //     // 1. Validasi Input
    // //     $request->validate([
    // //         'name'        => 'required|string|max:255',
    // //         'stock'       => 'required|integer|min:0',
    // //         'unit'        => 'required|string|max:50',
    // //         'supplier_id' => 'required|integer|exists:suppliers,id',
    // //     ]);

    // //     // 2. CARI DATA DI DATABASE DULU (Ini yang sebelumnya terlewat)
    // //     $material = Material::findOrFail($id);

    // //     // 3. Baru lakukan update pada data yang sudah ditemukan
    // //     $material->update([
    // //         'name'        => ucwords(strtolower($request->name)),
    // //         'unit'        => ucwords(strtolower($request->unit)),
    // //         'stock'       => $request->stock,
    // //         'supplier_id' => $request->supplier_id,
    // //     ]);

    // //     // 4. Redirect kembali dengan pesan sukses
    // //     return redirect()->back()->with('success', 'Data Material berhasil diperbarui!');
    // // }

    // // public function destroy(Material $id)
    // // {
    // //     // 1. Hapus Data Material
    // //     $id->delete();

    // //     // 2. Redirect kembali
    // //     return redirect()->back()->with('success', 'Data Material berhasil dihapus!');
    // // }

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
        ]);

        // 1. Buat data material global (kolom stock di table material bisa diabaikan/dikosongkan)
        $material = Material::create([
            'name'        => $request->name,
            'unit'        => $request->unit,
            'supplier_id' => $request->supplier_id,
        ]);

        // 2. Hubungkan material baru ini ke proyek yang sedang dibuka (Pivot Table)
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

        // 1. Update data global material (Nama, Unit, Supplier)
        $material->update([
            'name'        => $request->name,
            'unit'        => $request->unit,
            'supplier_id' => $request->supplier_id,
        ]);

        // 2. Update STOK khusus di dalam Proyek tersebut (Pivot Table)
        $project = Project::find($request->project_id);
        $project->materials()->updateExistingPivot($material->id, ['stock' => $request->stock]);

        return redirect()->back()->with('success', 'Data material dan stok berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        
        // Hapus (detach) relasi material ini dari proyek yang sedang dibuka
        $project = Project::find($request->project_id);
        $project->materials()->detach($material->id);

        return redirect()->back()->with('success', 'Material berhasil dikeluarkan dari proyek!');
    }   

    
}
