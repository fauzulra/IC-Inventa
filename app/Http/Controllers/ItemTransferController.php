<?php

namespace App\Http\Controllers;

use App\Models\ItemTransfer;
use App\Models\Material;
use App\Models\Order;
use App\Models\Project;
use App\Models\OutgoingGood;
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
    ], [
        // Pesan error kustom (opsional)
        'from_project_id.required' => 'Proyek asal wajib dipilih.',
        'quantity.min'             => 'Kuantitas minimal adalah 1.',
    ]);

    if ($request->from_project_id == $request->to_project_id) {
        return redirect()->back()->with('error', 'Gagal! Proyek tujuan tidak boleh sama dengan proyek asal.');
    }

    // =========================================================
    // LOGIKA VALIDASI STOK (Baru)
    // =========================================================
    $projectAsal = Project::findOrFail($request->from_project_id);
    
    // Mengambil data material spesifik dari pivot table proyek asal
    $materialInProject = $projectAsal->materials()->where('material_id', $request->material_id)->first();

    // Jika material tidak ditemukan di proyek asal ATAU stok kurang
    if (!$materialInProject || $materialInProject->pivot->stock < $request->quantity) {
        $stokTersedia = $materialInProject ? $materialInProject->pivot->stock : 0;
        $unit = $materialInProject ? $materialInProject->unit : '';

        return redirect()->back()
            ->with('error', "Stok tidak mencukupi! Tersedia hanya: {$stokTersedia} {$unit}.")
            ->withInput(); 
    }
    // =========================================================

    ItemTransfer::create(array_merge($request->all(), [
        'status' => 'pending'
    ]));

    return redirect()->back()->with('success', 'Pengajuan transfer barang berhasil dibuat!');
}

    public function orderUpdate(Request $request, $id)
    {
        $request->validate([
            'from_project_id' => 'required|integer|exists:projects,id',
            'material_id'     => 'required|integer|exists:materials,id',
            'quantity'        => 'required|integer|min:1',
            'transfer_date'   => 'required|date',
        ]);

        $transfer = ItemTransfer::findOrFail($id);

        // Keamanan: Hanya bisa edit jika status masih pending
        if (strtolower($transfer->status) !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan berstatus pending yang dapat diedit!');
        }

        if ($request->from_project_id == $transfer->to_project_id) {
            return redirect()->back()->with('error', 'Gagal! Proyek tujuan tidak boleh sama dengan proyek asal.');
        }

        $transfer->update([
            'from_project_id' => $request->from_project_id,
            'material_id'     => $request->material_id,
            'quantity'        => $request->quantity,
            'transfer_date'   => $request->transfer_date,
        ]);

        return redirect()->back()->with('success', 'Pengajuan transfer barang berhasil diperbarui!');
    }

    public function orderDestroy($id)
    {
        $transfer = ItemTransfer::findOrFail($id);

        // Keamanan: Hanya bisa dibatalkan jika status masih pending
        if (strtolower($transfer->status) !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan berstatus pending yang dapat dibatalkan!');
        }

        $transfer->delete();

        return redirect()->back()->with('success', 'Pengajuan transfer barang berhasil dibatalkan/dihapus!');
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

        // Mulai transaksi database agar aman
        DB::beginTransaction();

        try {
            // 1. Update status transfer
            $transfer->update([
                'status' => $request->status
            ]);

            // 2. Jika disetujui/diterima, proses pengurangan stok & catat barang keluar
            if (strtolower($request->status) === 'diterima') {
                
                $projectAsal = Project::findOrFail($transfer->from_project_id);
                $materialInProject = $projectAsal->materials()->where('material_id', $transfer->material_id)->first();

                // Validasi ulang untuk jaga-jaga jika stok tiba-tiba habis sebelum dikonfirmasi
                if (!$materialInProject || $materialInProject->pivot->stock < $transfer->quantity) {
                    DB::rollBack(); // Batalkan semua proses
                    return redirect()->back()->with('error', 'Gagal Konfirmasi! Sisa stok di proyek Anda saat ini tidak mencukupi untuk transfer ini.');
                }

                // A. Kurangi stok di Pivot Table (Gudang Asal)
                $newStock = $materialInProject->pivot->stock - $transfer->quantity;
                $projectAsal->materials()->updateExistingPivot($transfer->material_id, ['stock' => $newStock]);

                // B. Buat Catatan Barang Keluar (Sesuaikan nama kolom dengan tabel OutgoingGood Anda)
                OutgoingGood::create([
                    'source_project_id'      => $transfer->from_project_id,
                    'destination_project_id' => $transfer->to_project_id,
                    'material_id'            => $transfer->material_id,
                    'quantity'               => $transfer->quantity,
                    'date_shipped'           => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status transfer berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    

}
