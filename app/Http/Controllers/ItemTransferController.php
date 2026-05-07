<?php

namespace App\Http\Controllers;

use App\Models\ItemTransfer;
use App\Models\Material;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Keamanan: Jika bukan admin, tolak akses dan arahkan ke dashboard
        if (!$user->hasRole('admin')) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman histori global.');
        }

        // Ambil SEMUA data transfer dari database
        $transfers = ItemTransfer::with(['material', 'fromProject', 'toProject'])
                                 ->orderBy('created_at', 'desc')
                                 ->get();
                                 
        return view('itemtransfer.index', compact('transfers'));
    }

   public function orderIndex()
    {
        $user = auth()->user();
        
        // MENGGUNAKAN SPATIE: Jika BUKAN admin, lempar ke proyeknya
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('itemtransfer.order.show', $user->project_id);
        }

        // JIKA ADMIN: Tampilkan form pilih proyek (Meminjam view select_project)
        $projects = Project::paginate(5);
        $title = "Pengajuan Transfer Antar Proyek";
        $targetRoute = 'itemtransfer.order.show'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

   public function showProjectOrders($id)
    {
        $project = Project::findOrFail($id);
        
        // LOGIKA DIBALIK: Tampilkan riwayat transfer yang DIMINTA OLEH proyek ini (berarti to_project_id)
        $transfers = ItemTransfer::with(['material', 'fromProject'])
                                 ->where('to_project_id', $id) // <-- INI YANG BERUBAH
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        // Ambil semua daftar material dari database global untuk pilihan dropdown
        $materials = Material::orderBy('name', 'asc')->get(); 

        // Ambil semua proyek KECUALI proyek saat ini (Untuk dropdown "Minta Dari Proyek Mana?")
        $allProjects = Project::where('id', '!=', $id)->get();

        return view('itemtransfer.order', compact('project', 'transfers', 'materials', 'allProjects'));
    }

    public function getProjectMaterials($id)
    {
        // Cari proyek beserta relasi material dan stok di tabel pivot
        $project = Project::with('materials')->findOrFail($id);
        
        // Kembalikan datanya dalam format JSON untuk dibaca oleh JavaScript
        return response()->json($project->materials);
    }

    public function orderStore(Request $request)
    {
        $request->validate([
            'from_project_id' => 'required|integer|exists:projects,id',
            'to_project_id'   => 'required|integer|exists:projects,id',
            'material_id'     => 'required|integer|exists:materials,id',
            'quantity'        => 'required|integer|min:1',
            'transfer_date'   => 'required|date',
        ]);

        if ($request->from_project_id == $request->to_project_id) {
            return redirect()->back()->with('error', 'Gagal! Proyek tujuan tidak boleh sama dengan proyek asal.');
        }

        // Opsi Keamanan Ekstra: Bisa dicek dulu apakah quantity yang diminta melebihi sisa stok di Pivot Table Proyek Asal
        // ...

        ItemTransfer::create(array_merge($request->all(), [
            'status' => 'pending'
        ]));

        return redirect()->back()->with('success', 'Pengajuan transfer barang berhasil dibuat!');
    }


    // =========================================================================
    // BAGIAN 2: KONFIRMASI TRANSFER BARANG
    // =========================================================================
    public function confirmationIndex()
    {
        $user = auth()->user();
        
        // MENGGUNAKAN SPATIE: Jika BUKAN admin, lempar ke proyeknya
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('itemtransfer.confirmation.show', $user->project_id);
        }

        // JIKA ADMIN: Tampilkan form pilih proyek
        $projects = Project::paginate(5);
        $title = "Konfirmasi Transfer Masuk/Keluar";
        $targetRoute = 'itemtransfer.confirmation.show'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

    public function showProjectConfirmations($id)
    {
        $project = Project::findOrFail($id);
        
        // LOGIKA BARU: 
        // HANYA tampilkan transfer di mana proyek ini adalah GUDANG ASAL (yang dimintai barang)
        // Jangan tampilkan barang yang mereka request sendiri (karena itu ada di halaman Order)
        $transfers = ItemTransfer::with(['material', 'fromProject', 'toProject'])
                                 ->where('from_project_id', $id) // <-- INI KUNCINYA
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('itemtransfer.confirmation', compact('project', 'transfers'));
    }

    public function confirmationUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $transfer = ItemTransfer::findOrFail($id);

        // VALIDASI KEAMANAN: Cegah perubahan status jika sudah final
        if (in_array(strtolower($transfer->status), ['selesai', 'diterima', 'dibatalkan', 'ditolak'])) {
            return redirect()->back()->with('error', 'Status transfer yang sudah final tidak dapat diubah lagi!');
        }

        $transfer->update([
            'status' => $request->status
        ]);

        /* 
         * Catatan Fitur Lanjutan:
         * Jika Anda ingin otomatis memindahkan stok di database ketika status diubah menjadi "Diterima",
         * Anda bisa menuliskan logika pengurangan stok di Pivot `from_project` 
         * dan penambahan stok di Pivot `to_project` di bagian sini.
         */

        return redirect()->back()->with('success', 'Status transfer barang berhasil diperbarui!');
    }

    

}
