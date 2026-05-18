<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventa Cipta</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen flex">

        <div class="hidden lg:flex lg:w-1/2  relative">
            <img src="{{ asset('images/login-bg.png') }}" alt="Factory Background"
                class="absolute inset-0 w-full h-full object-cover">

            <div class="absolute bottom-0 left-0 right-0 bg-white/90 h-29.5 p-8 flex items-center justify-between z-10">
                <p class="text-gray-900 text-xl font-semibold">
                    Supported By
                </p>
                <img src="{{ asset('images/logo-cipta-small.png') }}" alt="Cipta Logo" class="h-16 w-auto">
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white px-8 py-6">
            <div class="w-full max-w-md space-y-4">

                <div class="text-center">
                    <img class="mx-auto h-40 w-auto" src="{{ asset('images/logo.png') }}" alt="Inventa Cipta">

                    <h2 class="mt-1 text-3xl font-bold text-gray-900">Log In</h2>
                </div>

                <form class="mt-4 space-y-4" action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Username Field --}}
                    <div class="">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-[#747474]" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" required value="{{ old('username') }}"
                                class="focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border">
                        </div>
                    </div>

                    {{-- Password Field with Toggle Eye --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-[#747474]" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>

                            {{-- Input Password --}}
                            <input type="password" name="password" id="password" required
                                class="focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 pr-10 sm:text-sm border-gray-300 rounded-md py-3 border">

                            {{-- Toggle Button (Eye Icon) --}}
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#747474] hover:text-gray-900 focus:outline-none">
                                {{-- Ikon Mata Terbuka (Default tersembunyi jika password tersembunyi, tapi karena ini awal, kita pakai icon mata normal) --}}
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-[#0065F8] hover:text-blue-800 underline">
                                Lupa Password?
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center pt-2">
                        <button type="submit"
                            class="group relative w-60 flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#8B4513] hover:bg-[#723b10] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-lg">
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 1. Import Library SweetAlert2 via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. Logika Pemanggilan SweetAlert & Toggle Password --}}
    <script>
        // FUNGSI TOGGLE PASSWORD VISIBILITY
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            // Cek tipe saat ini, ubah ke kebalikannya
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ubah SVG menjadi ikon mata dicoret (Tutup Mata)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                // Ubah SVG kembali ke ikon mata normal (Buka Mata)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {

            // SKENARIO 1: Menangkap error validasi bawaan Laravel
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: 'Username atau password yang Anda masukkan salah.',
                    confirmButtonColor: '#8B4513',
                    confirmButtonText: 'Coba Lagi'
                });
            @endif

            // SKENARIO 2: Menangkap error kustom dari Controller
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#8B4513',
                    confirmButtonText: 'OK'
                });
            @endif

            // SKENARIO 3: Menangkap pesan sukses
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#8B4513'
                });
            @endif

        });
    </script>
</body>

</html>
