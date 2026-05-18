@extends('layout.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-gray-800">Kelola Pengguna Sistem</h2>
        </div>

        <button onclick="toggleModal('modalTambahUser')"
            class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
            <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
        </button>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700 w-12 text-center">NO</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700">Nama Lengkap</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700">Username & Email</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700">No. HP</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700 text-center">Peran (Role)</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700">Penempatan Proyek</th>
                        <th class="border px-4 py-3 text-sm font-bold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-4 text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                            <td class="border px-4 py-4">
                                <span class="block text-gray-800">{{ $user->username }}</span>
                                <span class="block text-xs text-gray-500">{{ $user->email }}</span>
                            </td>
                            <td class="border px-4 py-4">{{ $user->phone ?? '-' }}</td>
                            <td class="border px-4 py-4 text-center">
                                {{-- Tombol klik untuk memicu modal status --}}
                                <button type="button"
                                    onclick="openStatusModal({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active }})"
                                    class="focus:outline-none hover:scale-105 transition-transform duration-200">
                                    @if ($user->is_active)
                                        <span
                                            class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold cursor-pointer">Aktif</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold cursor-pointer">Tidak
                                            Aktif</span>
                                    @endif
                                </button>
                            </td>
                            <td class="border px-4 py-4 text-center">
                                @php
                                    $roleName = $user->roles->first()->name ?? 'Tanpa Role';

                                    // Tentukan warna default (Abu-abu) jika rolenya tidak dikenali
                                    $colorClass = 'bg-gray-100 text-gray-800';

                                    if ($roleName == 'logistik') {
                                        $colorClass = 'bg-blue-400 text-white';
                                    } elseif ($roleName == 'staff') {
                                        // Menggunakan yellow (kuning). Bisa diganti ke orange-100/orange-800 jika kurang terang
                                        $colorClass = 'bg-yellow-400 text-white';
                                    } elseif ($roleName == 'admin') {
                                        $colorClass = 'bg-green-400 text-white';
                                    }
                                @endphp

                                <span
                                    class="{{ $colorClass }} px-2 py-1 rounded text-xs font-semibold capitalize border border-white/20 shadow-sm">
                                    {{ str_replace('_', ' ', $roleName) }}
                                </span>
                            </td>
                            <td class="border px-4 py-4">
                                {{ $user->project ? $user->project->name : 'Semua Proyek (Pusat)' }}
                            </td>
                            <td class="border px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button
                                        onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->username }}', '{{ $user->phone }}', '{{ $user->roles->first()->name ?? '' }}')"
                                        class="text-orange-400 border border-orange-400 hover:bg-orange-50 rounded p-1 w-8 h-8 flex items-center justify-center transition">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Tombol Delete --}}
                                    <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                        class="text-red-500 border border-red-500 hover:bg-red-50 rounded p-1 w-8 h-8 flex items-center justify-center transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH USER --}}
    <div id="modalTambahUser" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalTambahUser')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Registrasi Pengguna Baru</h3>
                        <button onclick="toggleModal('modalTambahUser')" class="text-gray-400 hover:text-gray-500"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-orange-400 focus:border-transparent">
                        </div>

                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                                <input type="text" name="username" required
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-orange-400">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <input type="email" name="email" required
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-orange-400">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Password Sementara</label>
                            <div class="relative">
                                <input type="password" name="password" id="passwordSementara" required
                                    placeholder="Minimal 8 Karakter"
                                    class="shadow border rounded w-full py-2 px-3 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">

                                {{-- Tombol Mata --}}
                                <button type="button" onclick="togglePasswordSementara()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg id="eyeIconSementara" class="h-5 w-5" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Berikan password ini ke staf, mereka bisa meresetnya
                                nanti.</p>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Role</label>
                                <select name="role" id="roleSelect" required
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                    <option value="" disabled selected>Pilih Peran...</option>
                                    @foreach ($roles as $role)
                                        {{-- MODIFIKASI: Sembunyikan role admin --}}
                                        @if ($role->name !== 'admin')
                                            <option value="{{ $role->name }}"
                                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Penempatan Proyek</label>
                                {{-- MODIFIKASI: Tambah required karena user non-admin wajib punya proyek --}}
                                <select name="project_id" id="projectSelect" required
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                    {{-- MODIFIKASI: Ubah opsi default dan hilangkan pilihan Pusat --}}
                                    <option value="" disabled selected>-- Pilih Proyek --</option>
                                    @foreach ($projects as $proj)
                                        @php
                                            $infoStaff = $proj->has_staff ? 'Terisi' : 'Kosong';
                                            $infoLog = $proj->has_logistik ? 'Terisi' : 'Kosong';
                                        @endphp

                                        <option value="{{ $proj->id }}"
                                            data-has-staff="{{ $proj->has_staff ? 1 : 0 }}"
                                            data-has-logistik="{{ $proj->has_logistik ? 1 : 0 }}"
                                            {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                            {{ $proj->name }} (Staf: {{ $infoStaff }}, Log: {{ $infoLog }})
                                        </option>
                                    @endforeach
                                </select>
                                <p id="projectHelpText" class="text-xs text-gray-500 mt-1">Pilih peran terlebih dahulu.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">No. HP / WhatsApp</label>
                                <input type="text" name="phone"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-orange-400 focus:border-transparent"
                                    placeholder="Contoh: 08123456789">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Status Akun</label>
                                <div class="flex items-center gap-6 py-2">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="is_active" value="1" required
                                            class="w-4 h-4 text-[#FFB22C] bg-gray-100 border-gray-300 focus:ring-orange-400 focus:ring-2"
                                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktif</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="is_active" value="0" required
                                            class="w-4 h-4 text-[#FFB22C] bg-gray-100 border-gray-300 focus:ring-orange-400 focus:ring-2"
                                            {{ old('is_active') == '0' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm font-medium text-gray-700">Tidak Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Akun
                            </button>
                            <button type="button" onclick="toggleModal('modalTambahUser')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================== --}}
    {{-- MODAL UPDATE STATUS AKUN                       --}}
    {{-- ============================================== --}}
    <div id="modalKonfirmasiStatus" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeStatusModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Update Status Akun</h3>
                        <button onclick="closeStatusModal()"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form id="statusForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pengguna</label>
                            <input type="text" id="modalUserName" value="" readonly
                                class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none font-medium">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-3">Pilih Status Baru:</label>
                            <div class="space-y-3">
                                {{-- OPSI AKTIF --}}
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition group">
                                    <input type="radio" name="is_active" value="1" id="radioAktif" required
                                        class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300">
                                    <span
                                        class="ml-3 flex-1 block font-medium text-gray-700 group-hover:text-green-600">Aktif
                                        (Bisa Login)</span>
                                    <span
                                        class="bg-green-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Aktif</span>
                                </label>

                                {{-- OPSI TIDAK AKTIF --}}
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-red-50 transition group">
                                    <input type="radio" name="is_active" value="0" id="radioTidakAktif" required
                                        class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300">
                                    <span
                                        class="ml-3 flex-1 block font-medium text-gray-700 group-hover:text-red-600">Cabut
                                        Akses Login</span>
                                    <span class="bg-red-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Tidak
                                        Aktif</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeStatusModal()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT USER --}}
    <div id="modalEditUser" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalEditUser')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Edit Data Pengguna</h3>
                        <button type="button" onclick="toggleModal('modalEditUser')"
                            class="text-gray-400 hover:text-gray-500"><i class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama & Email (READONLY) --}}
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                                <input type="text" id="edit_name" readonly
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 bg-gray-100 cursor-not-allowed">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <input type="email" id="edit_email" readonly
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 bg-gray-100 cursor-not-allowed">
                            </div>
                        </div>

                        {{-- Username & No HP (EDITABLE) --}}
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Username Baru</label>
                                <input type="text" name="username" id="edit_username" required
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-orange-400 focus:border-transparent">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">No. HP / WhatsApp</label>
                                <input type="text" name="phone" id="edit_phone"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring-orange-400 focus:border-transparent">
                            </div>
                        </div>

                        {{-- Role (EDITABLE) --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Peran (Role)</label>
                            <select name="role" id="edit_role" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update Data
                            </button>
                            <button type="button" onclick="toggleModal('modalEditUser')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE USER --}}
    <div id="modalDeleteUser" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalDeleteUser')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Hapus Pengguna</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin ingin menghapus akun <b id="delete_name"></b>?
                        Data yang dihapus tidak dapat dikembalikan.</p>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus
                                Akun</button>
                            <button type="button" onclick="toggleModal('modalDeleteUser')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- Import Library SweetAlert2 via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fungsi untuk membuka/menutup modal
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }

        document.addEventListener("DOMContentLoaded", function() {

            // =========================================================
            // 1. NOTIFIKASI SWEETALERT (Error & Success Handling)
            // =========================================================

            // Jika ada pesan Error Validasi bawaan Laravel
            @if ($errors->any())
                toggleModal('modalTambahUser');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    text: 'Silakan periksa kembali isian form Anda.',
                    confirmButtonColor: '#FFB22C'
                });
            @endif

            // Jika ada pesan Error Kustom (seperti validasi Role dari Controller)
            @if (session('error'))
                toggleModal('modalTambahUser');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            @endif

            // Jika Berhasil Disimpan
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#28a745'
                });
            @endif

            // =========================================================
            // 2. LOGIKA DINAMIS: KUNCI PROYEK YANG SUDAH PENUH
            // =========================================================
            const roleSelect = document.getElementById('roleSelect');
            const projectSelect = document.getElementById('projectSelect');
            const projectHelpText = document.getElementById('projectHelpText');

            function checkProjectQuota() {
                // Pastikan elemen ada di halaman agar tidak error
                if (!roleSelect || !projectSelect) return;

                let selectedRole = roleSelect.value;
                let options = projectSelect.options;
                let availableProjects = 0;

                for (let i = 0; i < options.length; i++) {
                    let option = options[i];

                    // Skip opsi pertama "-- Pusat (Semua Proyek) --"
                    if (option.value === "") {
                        // Jika role yang dipilih admin, paksa pilih opsi "-- Pusat --"
                        if (selectedRole === 'admin') {
                            option.selected = true;
                        }
                        continue;
                    }

                    // Ambil data ketersediaan dari atribut HTML
                    let hasStaff = parseInt(option.getAttribute('data-has-staff'));
                    let hasLogistik = parseInt(option.getAttribute('data-has-logistik'));

                    // Reset opsi menjadi bisa diklik terlebih dahulu
                    option.disabled = false;

                    // Logika Pemblokiran (Disable)
                    if (selectedRole === 'logistik' && hasLogistik === 1) {
                        option.disabled = true;
                    } else if (selectedRole === 'staff' && hasStaff === 1) {
                        option.disabled = true;
                    } else {
                        availableProjects++;
                    }
                }

                // Jika opsi yang sedang terpilih ternyata di-disable oleh sistem, kosongkan!
                if (projectSelect.options[projectSelect.selectedIndex]?.disabled) {
                    projectSelect.value = "";
                }

                // Ganti teks bantuan di bawah dropdown
                if (selectedRole === 'admin') {
                    projectHelpText.innerHTML = "Admin otomatis di Pusat (Semua Proyek).";
                    projectSelect.disabled = true; // Kunci dropdown khusus admin
                } else {
                    projectSelect.disabled = false; // Buka kunci dropdown
                    if (availableProjects === 0 && selectedRole !== "") {
                        projectHelpText.innerHTML = '<span class="text-red-600 font-semibold">Maaf, kuota ' +
                            selectedRole + ' di semua proyek sudah penuh!</span>';
                    } else {
                        projectHelpText.innerHTML = "Pilih proyek yang masih kosong.";
                    }
                }
            }

            // Jalankan event listener saat peran (role) diubah
            if (roleSelect) {
                roleSelect.addEventListener('change', checkProjectQuota);
            }

            // Jalankan fungsi sekali saat modal/halaman dimuat 
            // (Sangat penting agar saat halaman direfresh karena error, opsi proyek yang penuh tetap terkunci)
            checkProjectQuota();
        });

        // Fungsi untuk Buka Modal Status
        function openStatusModal(userId, userName, currentStatus) {
            document.getElementById('modalKonfirmasiStatus').classList.remove('hidden');

            // Masukkan nama pengguna ke input readonly
            document.getElementById('modalUserName').value = userName;

            // Set URL action form secara dinamis berdasarkan ID
            document.getElementById('statusForm').action = `/users/${userId}/status`;

            // Centang otomatis radio button sesuai status saat ini
            if (currentStatus === 1) {
                document.getElementById('radioAktif').checked = true;
            } else {
                document.getElementById('radioTidakAktif').checked = true;
            }
        }

        // Fungsi untuk Tutup Modal Status
        function closeStatusModal() {
            document.getElementById('modalKonfirmasiStatus').classList.add('hidden');
        }

        function togglePasswordSementara() {
            const passwordInput = document.getElementById('passwordSementara');
            const eyeIcon = document.getElementById('eyeIconSementara');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ubah SVG menjadi ikon mata dicoret
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                // Ubah SVG kembali ke ikon mata normal
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Fungsi untuk Buka Modal Edit
        function openEditModal(id, name, email, username, phone, role) {
            document.getElementById('editUserForm').action = `/users/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_phone').value = (phone !== '-') ? phone : '';
            document.getElementById('edit_role').value = role;

            toggleModal('modalEditUser');
        }

        // Fungsi untuk Buka Modal Hapus
        function openDeleteModal(id, name) {
            document.getElementById('deleteUserForm').action = `/users/${id}`;
            document.getElementById('delete_name').innerText = name;

            toggleModal('modalDeleteUser');
        }
    </script>

    @include('layout.script')
@endsection
