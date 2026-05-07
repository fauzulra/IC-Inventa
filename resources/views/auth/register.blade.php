<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Inventa Cipta</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-sans antialiased relative min-h-screen flex items-center justify-center bg-gray-100 py-4"
    style="background-image: url('{{ asset('images/login-bg.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;">

    <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px]"></div>
    <div class="relative z-10 w-full max-w-5xl px-2 flex flex-col items-center">

        <div
            class="bg-white rounded-2xl shadow-xl w-full sm:p-10 border border-gray-100 flex flex-col md:flex-row items-stretch">

            <div class="w-full md:w-5/12 flex flex-col pr-0 md:pr-4">

                <div>
                    <h2 class="text-3xl font-bold  text-gray-900 ">Registrasi</h2>
                    <p class="text-sm text-gray-500 pt-2">Harap lengkapi untuk membuat akun Anda</p>
                </div>

                <div class="flex-grow flex flex-col items-center justify-center mt-8 mb-8">
                    <img src="{{ asset('images/robot.png') }}" alt="Ilustrasi Registrasi"
                        class="w-full max-w-70 object-contain drop-shadow-lg">
                </div>

                <div class="mt-auto text-center mx-auto md:text-left pb-2">
                    <p class="text-sm text-gray-700 font-medium drop-shadow-sm">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="text-[#0065F8] hover:text-blue-800 hover:underline transition ml-1">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>

            <div class="w-full md:w-7/12 mt-10 md:mt-0 flex flex-col">

                <form action="{{ route('register') }}" method="POST" class="space-y-2 flex flex-col h-full">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Oops! Ada kesalahan:</strong>
                            <ul class="list-disc pl-5 mt-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama
                            Lengkap</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" required value="{{ old('username') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">No HP</label>
                        <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                    </div>

                    {{-- DROPDOWN ROLE (Sudah ada di kode Anda) --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Daftar
                            Sebagai</label>
                        <div class="relative">
                            <select name="role" id="role" required
                                class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white transition">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran...
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="logistik" {{ old('role') == 'logistik' ? 'selected' : '' }}>Logistik
                                </option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>
                                    Staff</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN BARU: DROPDOWN PROYEK (Disembunyikan default) --}}
                    <div id="projectContainer" class="hidden pt-2">
                        <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1">Tempat Bertugas
                            (Proyek)</label>
                        <div class="relative">
                            <select name="project_id" id="project_id"
                                class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white transition">
                                <option value="" disabled selected>-- Pilih Proyek --</option>

                                @foreach ($projects as $project)
                                    <!--
                                      Simpan data kuota ke atribut 'data-logistik-count' dan 'data-staf-count'.
                                      Ini akan dibaca oleh JavaScript nanti.
                                    -->
                                    <option value="{{ $project->id }}"
                                        data-logistik-count="{{ $project->logistik_count }}"
                                        data-staf-count="{{ $project->staf_count }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->code }} - {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <p id="projectHelpText" class="text-xs text-orange-500 mt-1 italic drop-shadow-sm">Wajib dipilih
                            untuk Staf Lapangan & Logistik.</p>
                    </div>
                    {{-- <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Daftar
                            Sebagai</label>
                        <div class="relative">
                            <select name="role" id="role" required
                                class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white transition">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran...
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="logistik" {{ old('role') == 'logistik' ? 'selected' : '' }}>Logistik
                                </option>
                                <option value="staf_lapangan" {{ old('role') == 'staf_lapangan' ? 'selected' : '' }}>
                                    Staf Lapangan</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>

                        </div>
                    </div> --}}

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">

                        <div class="pt-4 flex justify-end mt-auto">
                            <button type="submit"
                                class="w-full sm:w-48 bg-[#7C3F2B] hover:bg-[#633222] text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200">
                                Daftar
                            </button>
                        </div>

                </form>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Jika ada pesan sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false, // Menyembunyikan tombol OK
                timer: 3000 // Auto close 3 detik
            });
        @endif

        // Jika ada pesan error dari session
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Jika ada error validasi input
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ada kesalahan pada data yang Anda masukkan. Silakan periksa kembali.',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Logika untuk memunculkan/menyembunyikan pilihan proyek & Filter Kuota
        const roleSelect = document.getElementById('role');
        const projectContainer = document.getElementById('projectContainer');
        const projectSelect = document.getElementById('project_id');
        const projectHelpText = document.getElementById('projectHelpText');

        function toggleProject() {
            let selectedRole = roleSelect.value;

            // 1. Logika Tampilkan/Sembunyikan Dropdown Proyek
            if (selectedRole === 'staff' || selectedRole ===
                'logistik') { // Note: Sesuaikan 'staff' dengan value dropdown Anda
                projectContainer.classList.remove('hidden');
                projectSelect.setAttribute('required', 'required');

                // 2. Logika Cek Kuota per Opsi Proyek
                let options = projectSelect.options;
                let availableProjects = 0; // Menghitung berapa proyek yang masih kosong

                for (let i = 0; i < options.length; i++) {
                    let option = options[i];

                    // Skip opsi default ("-- Pilih Proyek --")
                    if (option.value === "") continue;

                    let logistikCount = parseInt(option.getAttribute('data-logistik-count'));
                    let stafCount = parseInt(option.getAttribute('data-staf-count'));

                    // Reset state opsi
                    option.disabled = false;
                    // Hapus teks tambahan "(Penuh)" jika ada dari pengecekan sebelumnya
                    option.text = option.text.replace(" (Penuh)", "");

                    // Jika mendaftar sebagai Logistik dan kuotanya sudah >= 1
                    if (selectedRole === 'logistik' && logistikCount >= 1) {
                        option.disabled = true;
                        option.text += " (Penuh)";
                    }
                    // Jika mendaftar sebagai Staf dan kuotanya sudah >= 1
                    else if (selectedRole === 'staff' && stafCount >= 1) {
                        option.disabled = true;
                        option.text += " (Penuh)";
                    } else {
                        // Jika tidak di-disable, berarti proyek ini masih tersedia
                        availableProjects++;
                    }
                }

                // 3. Kosongkan pilihan jika ternyata opsi yang tadinya dipilih sekarang di-disable
                if (projectSelect.options[projectSelect.selectedIndex]?.disabled) {
                    projectSelect.value = "";
                }

                // 4. Update teks bantuan jika semua proyek sudah penuh untuk role tersebut
                if (availableProjects === 0) {
                    projectHelpText.innerHTML =
                        `<span class="text-red-600 font-semibold">Maaf, kuota untuk peran ini di semua proyek sudah penuh.</span>`;
                } else {
                    projectHelpText.innerHTML = "Wajib dipilih untuk Staf Lapangan & Logistik.";
                }

            } else {
                // Jika pilih Admin
                projectContainer.classList.add('hidden');
                projectSelect.removeAttribute('required');
                projectSelect.value = ''; // Kosongkan pilihan
            }
        }

        // Jalankan saat role diubah
        roleSelect.addEventListener('change', toggleProject);

        // Jalankan sekali saat halaman dimuat
        window.addEventListener('load', toggleProject);
    </script>

</body>

</html>
