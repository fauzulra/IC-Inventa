<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; 

class UserController extends Controller
{
    public function index()
    {
        // Pastikan hanya admin yang bisa masuk
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak!');
        }

        $users = User::with(['roles', 'project'])->latest()->get();
        $roles = Role::orderBy('name', 'asc')->get();
        
        $projects = Project::orderBy('name', 'asc')->get()->map(function($project) {
            // Gunakan exists() agar query lebih cepat daripada count()
            $project->has_staff = User::where('project_id', $project->id)->role('staff')->exists();
            $project->has_logistik = User::where('project_id', $project->id)->role('logistik')->exists();
            
            return $project;
        });

        return view('users.index', compact('users', 'roles', 'projects'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Akses Ditolak!');
        }

        $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string',
            'role'       => 'required|exists:roles,name',
            'project_id' => 'nullable|exists:projects,id',
            'phone' => 'nullable|string|max:20',
            'is_active'    => 'required|boolean',
        ]);

        if (in_array($request->role, ['staff', 'logistik']) && $request->project_id) {
            
            // Cek apakah di project_id ini sudah ada user dengan role tersebut
            $isRoleTaken = User::where('project_id', $request->project_id)
                               ->role($request->role) // Menggunakan scope bawaan Spatie Permission
                               ->exists();

            if ($isRoleTaken) {
                // Tentukan nama tampilan untuk pesan error
                $roleName = $request->role == 'staff' ? 'Staf Lapangan' : 'Logistik';
                
                // Kembalikan ke halaman index dengan pesan error SweetAlert dan pertahankan inputan
                return back()->with('error', "Gagal! Proyek ini sudah memiliki akun {$roleName}. Setiap proyek maksimal hanya boleh memiliki 1 {$roleName}.")
                             ->withInput(); 
            }
        }

        // 1. Buat User Baru
        $user = User::create([
            'name'       => $request->name,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password), 
            'phone' => $request->phone,
            'is_active'    => $request->is_active,
            'project_id' => $request->project_id,
        ]);

        // Berikan Role
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    // =========================================================================
    // FUNGSI UNTUK UPDATE STATUS AKUN
    // =========================================================================
    public function updateStatus(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Akses Ditolak!');
        }

        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user->update([
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Status akun ' . $user->name . ' berhasil diperbarui!');
    }

   
}