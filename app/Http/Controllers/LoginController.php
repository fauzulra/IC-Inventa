<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.welcome');
    }

    public function store(Request $request)
    {
        // 1. Validasi inputan
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // 2. Cek kecocokan data ke database
        if (Auth::attempt($credentials)) {
            // Jika berhasil, buat sesi baru (untuk keamanan)
            $request->session()->regenerate();
            
            // PERUBAHAN: Tambahkan pesan sukses di sini
            return redirect()->intended('/')->with('success', 'Login berhasil! Selamat datang kembali.'); 
        }

        // 4. Jika login gagal (username/password salah)
        // PERUBAHAN: Gunakan with('error') agar ditangkap oleh SweetAlert error spesifik
        return back()->with('error', 'Username atau password yang Anda masukkan salah.')
                     ->onlyInput('username'); 
    }

    // Fungsi untuk Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Hapus sesi pengguna untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }

    public function showRegistrationForm()
    {   
        $projects = Project::withCount([
            'users as logistik_count' => function ($query) {
                $query->role('logistik');
            },
            'users as staf_count' => function ($query) {
                $query->role('staff'); 
            }
        ])->orderBy('name', 'asc')->get();

        return view('auth.register', compact('projects')); 
    }

    public function register(Request $request)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users', // Harus unik, tidak boleh sama dengan user lain
            'email'    => 'required|string|email|max:255|unique:users',
            'role'     => 'required|string',
            'password' => 'required|string', 
            'project_id' => 'required_if:role,staf_lapangan,logistik|nullable|exists:projects,id',
        ], [
            // Pesan error custom agar admin paham
            'project_id.required_if' => 'Proyek wajib dipilih untuk Staf Lapangan dan Logistik!'
        ]);   

        // =====================================================================
        // 2. LOGIKA BARU: CEK KUOTA PERAN DI DALAM PROYEK (Maksimal 1)
        // =====================================================================
        if (in_array($request->role, ['staff', 'logistik']) && $request->project_id) {
            
            // Cek apakah di project_id ini sudah ada user dengan role tersebut
            $isRoleTaken = User::where('project_id', $request->project_id)
                               ->role($request->role) // Menggunakan scope bawaan Spatie Permission
                               ->exists();

            if ($isRoleTaken) {
                // Tentukan nama tampilan untuk pesan error
                $roleName = $request->role == 'staff' ? 'Staf Lapangan' : 'Logistik';
                
                // Kembalikan ke halaman register dengan pesan error SweetAlert dan pertahankan inputan sebelumnya
                return back()->with('error', "Gagal! Proyek ini sudah memiliki akun {$roleName}. Setiap proyek maksimal hanya boleh memiliki 1 {$roleName}.")
                             ->withInput(); 
            }
        }

        // 2. Simpan User Baru ke Database
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
            'project_id' => $request->role == 'admin' ? null : $request->project_id,
        ]);

        $user->assignRole($request->role);

        // 3. Redirect kembali ke halaman login dengan pesan sukses
        return back()->with('success', 'Registrasi berhasil! Silakan login menggunakan akun Anda.');
    }

    public function showForm()
    {
        return view('auth.forget'); 
    }

    // Fungsi untuk memproses saat tombol "Kirim" ditekan
    public function sendResetLink(Request $request)
    {
        // 1. Validasi input email
        $request->validate([
            'email' => 'required|email',
        ]);

        // 2. Di sini nantinya Anda bisa menambahkan logika Laravel bawaan (Password Broker) 
        // atau logika pengiriman email manual Anda.

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses sementara
        return back()->with('success', 'Jika email terdaftar, tautan reset password akan dikirimkan.');
    }
    
}
