<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\OutgoingGood;
use App\Models\Project;
use App\Models\IncomingGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingGoodController extends Controller
{
    /**
     * TAMPILAN INDEX (Pintu Masuk Utama)
     */
    /**
     * TAMPILAN INDEX (Pintu Masuk Utama)
     */
    public function index()
    {
        $user = auth()->user();
        
        // KUNCI PENYELESAIAN MASALAH:
        // Jika BUKAN admin (berarti Staf / Logistik), langsung lempar ke proyeknya sendiri!
        if (!$user->hasRole('admin') && $user->project_id) {
            return redirect()->route('outgoinggood.project', $user->project_id);
        }

        // Jika ADMIN: Tampilkan form pilih proyek
        $projects = Project::paginate(5);
        $title = "Pilih Proyek - Barang Keluar";
        $targetRoute = 'outgoinggood.project'; 
        
        return view('material.select_project', compact('projects', 'title', 'targetRoute'));
    }

    /**
     * TAMPILAN PER PROYEK (UNTUK LOGISTIK, STAF, DAN ADMIN)
     */
    public function showProjectOutgoing($id)
    {
        $user = auth()->user();

        // Keamanan: Cegah staf/logistik mengintip proyek lain
        if (!$user->hasRole('admin') && $user->project_id != $id) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda hanya diizinkan mengakses proyek Anda sendiri.');
        }

        $project = Project::findOrFail($id);
        
        $outgoingGoods = OutgoingGood::with(['material', 'destinationProject'])
                                     ->where('source_project_id', $id) 
                                     ->latest()
                                     ->get();

        $materials = $project->materials()->orderBy('name', 'asc')->get();
        $projects = Project::orderBy('name', 'asc')->get();

        return view('outgoinggood.index', compact('project', 'outgoingGoods', 'materials', 'projects'));
    }

    /**
     * MENYIMPAN DATA (KHUSUS LOGISTIK)
     */
    public function store(Request $request)
    {
        // BLOK KEAMANAN EXTRA: Pastikan HANYA Logistik yang bisa memproses form ini
        if (!auth()->user()->hasRole('logistik')) {
            return redirect()->back()->with('error', 'Akses Ditolak! Hanya Logistik yang berwenang mengeluarkan barang.');
        }

        $request->validate([
            'source_project_id'      => 'required|exists:projects,id',
            'destination_project_id' => 'required|exists:projects,id',
            'material_id'            => 'required|exists:materials,id',
            'quantity'               => 'required|integer|min:1',
            'date_shipped'           => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // ==============================================================
            // 1. PROSES BARANG KELUAR (PROYEK ASAL)
            // ==============================================================
            $projectAsal = Project::findOrFail($request->source_project_id);
            $materialAsal = $projectAsal->materials()->where('material_id', $request->material_id)->first();

            // Pengecekan Stok Pivot Proyek Asal
            if (!$materialAsal || $materialAsal->pivot->stock < $request->quantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal! Sisa stok material di proyek ini tidak mencukupi.');
            }

            // Kurangi stok di PIVOT Proyek Asal
            $projectAsal->materials()->updateExistingPivot($materialAsal->id, [
                'stock' => $materialAsal->pivot->stock - $request->quantity
            ]);

            // Simpan riwayat Barang Keluar
            OutgoingGood::create([
                'source_project_id'      => $request->source_project_id,
                'destination_project_id' => $request->destination_project_id,
                'material_id'            => $request->material_id,
                'quantity'               => $request->quantity,
                'date_shipped'           => $request->date_shipped,
            ]);

            // ==============================================================
            // 2. PROSES OTOMATIS BARANG MASUK (PROYEK TUJUAN)
            // ==============================================================
            if ($request->source_project_id != $request->destination_project_id) {
                
                // Simpan riwayat Barang Masuk ke Proyek Tujuan
                IncomingGood::create([
                    'project_id'    => $request->destination_project_id,
                    'material_id'   => $request->material_id,
                    'supplier_id'   => null, // Null karena ini transfer internal, bukan dari supplier luar
                    'quantity'      => $request->quantity,
                    'date_received' => $request->date_shipped, // Tanggal masuk disamakan dengan tanggal dikirim
                ]);

                // Tambah stok di Pivot Proyek Tujuan
                $projectTujuan = Project::findOrFail($request->destination_project_id);
                $materialDiTujuan = $projectTujuan->materials()->where('material_id', $request->material_id)->first();

                // Jika material sudah ada di proyek tujuan, tambahkan stoknya
                if ($materialDiTujuan) {
                    $newStock = $materialDiTujuan->pivot->stock + $request->quantity;
                    $projectTujuan->materials()->updateExistingPivot($request->material_id, ['stock' => $newStock]);
                } else {
                    // Jika material belum pernah ada di proyek tujuan, lampirkan (attach) data baru
                    $projectTujuan->materials()->attach($request->material_id, ['stock' => $request->quantity]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Barang keluar berhasil dicatat & otomatis masuk ke Proyek Tujuan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}