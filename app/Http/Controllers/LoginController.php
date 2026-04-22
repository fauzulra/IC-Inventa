<?php

namespace App\Http\Controllers;

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
            // Default jika tidak punya role khusus
            return redirect()->intended('/'); 
        }

        // 4. Jika login gagal (username/password salah)
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username'); // Mengembalikan isi kolom username agar tidak perlu ketik ulang
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
        return view('auth.register'); 
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
        ]);

        // 2. Simpan User Baru ke Database
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        $user->assignRole($request->role);

        // 3. Redirect kembali ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun Anda.');
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
