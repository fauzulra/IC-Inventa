<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('project.index', compact('projects'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (tanpa memvalidasi 'code' karena tidak dikirim dari form)
        $request->validate([
            'name'              => 'required|string|max:255',
            'location'          => 'required|string|max:255',
            'logistics_contact' => 'required|string|max:255',
        ]);

        // 2. Generate Inisial Nama Proyek (Misal: "Cipta Piayu" -> "CP")
        $words = explode(' ', $request->name);
        $initials = '';
        foreach ($words as $word) {
            // Ambil huruf pertama tiap kata, jadikan huruf besar
            $initials .= strtoupper(substr($word, 0, 1)); 
        }

        // 3. Pengecekan Duplikat dengan Format Angka 001, 002, dst
        $counter = 1;
        
        // Gabungkan inisial dengan angka format 3 digit (contoh: CP001)
        $code = $initials . sprintf('%03d', $counter);
        
        // Selama kode tersebut sudah ada di database, naikkan angka counter-nya
        while (Project::where('code', $code)->exists()) {
            $counter++;
            // Generate ulang kode dengan angka yang baru
            $code = $initials . sprintf('%03d', $counter);
        }

        // 4. Simpan ke Database
        Project::create([
            'code'              => $code, // Masukkan kode yang sudah di-generate otomatis
            'name'              => ucwords(strtolower($request->name)),
            'location'          => ucwords(strtolower($request->location)),
            'logistics_contact' => $request->logistics_contact,
        ]);

        // 5. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data Proyek berhasil ditambahkan!');
    }
}
