<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // 1. Menampilkan Halaman View (yang kodenya Anda kirim di atas)
    public function showForm()
    {
        return view('auth.forget'); 
    }

    // 2. Memproses Pengiriman Email
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi input email
        $request->validate(['email' => 'required|email']);

        // Laravel otomatis mengirimkan email link reset menggunakan fungsi bawaan Password broker
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Cek status apakah sukses terkirim atau gagal (email tidak ditemukan)
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
        }

        return back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }

    // ... (Fungsi showForm dan sendResetLinkEmail sebelumnya)

    // =========================================================
    // FUNGSI BARU: Menampilkan form ganti password (dari link email)
    // =========================================================
    public function showResetForm(Request $request, $token = null)
    {
        // $request->email ini diambil dari query string pada link email Laravel
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        // 1. Validasi input dari form reset password
        // Aturan 'confirmed' otomatis akan mengecek input 'password_confirmation'
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed',
        ]);

        // 2. Proses reset password menggunakan broker bawaan Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Jika token dan email valid, update password di tabel users
                $user->forceFill([
                    'password' => Hash::make($password) // Enkripsi password baru
                ])->save();
            }
        );

        // 3. Cek status keberhasilan
        // Jika sukses, lempar kembali ke halaman login dengan pesan sukses
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password Anda berhasil direset! Silakan login dengan password baru.');
        }

        // Jika gagal (misal token kadaluarsa atau email salah), kembalikan ke form
        return back()->withErrors(['email' => 'Gagal mereset password. Pastikan link token masih berlaku dan email sesuai.']);
    }
}