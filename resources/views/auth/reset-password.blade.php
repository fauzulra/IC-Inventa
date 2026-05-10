<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Inventa Cipta</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-sans antialiased relative min-h-screen flex items-center justify-center bg-gray-100"
    style="background-image: url('{{ asset('images/login-bg.png') }}'); background-size: cover; background-position: center;">

    <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px]"></div>

    <div class="relative z-10 w-full max-w-lg px-6 flex flex-col items-center">

        <div class="mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Inventa Cipta" class="h-24 drop-shadow-md">
        </div>

        <div class="bg-white rounded-2xl shadow-xl w-full p-8 sm:p-10 border border-gray-100">

            <h2 class="text-2xl font-bold text-center text-black mb-4">
                Buat Password Baru
            </h2>

            <p class="text-sm text-gray-600 text-center mb-8">
                Silakan buat password baru Anda. Pastikan password mudah diingat namun sulit ditebak.
            </p>

            {{-- Notifikasi Error Global --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form memanggil route update password --}}
            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                {{-- Wajib mengirimkan Token dari URL --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Wajib mengirimkan email --}}
                <input type="hidden" name="email" value="{{ $email }}">

                {{-- Input: Password Baru --}}
                <div class="mb-5 relative">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600">
                            <i class="fas fa-lock text-lg"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-10 py-3 border border-gray-500 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                            placeholder="Minimal 8 karakter" id="passwordInput">
                        {{-- Tombol Lihat/Sembunyikan Password --}}
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500 hover:text-gray-700"
                            onclick="togglePassword('passwordInput', 'eyeIcon1')">
                            <i class="fas fa-eye-slash" id="eyeIcon1"></i>
                        </div>
                    </div>
                </div>

                {{-- Input: Konfirmasi Password Baru --}}
                <div class="mb-8 relative">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600">
                            <i class="fas fa-lock text-lg"></i>
                        </div>
                        <input type="password" name="password_confirmation" required
                            class="w-full pl-10 pr-10 py-3 border border-gray-500 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                            placeholder="Ketik ulang password baru" id="passwordConfirmInput">
                        {{-- Tombol Lihat/Sembunyikan Password --}}
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500 hover:text-gray-700"
                            onclick="togglePassword('passwordConfirmInput', 'eyeIcon2')">
                            <i class="fas fa-eye-slash" id="eyeIcon2"></i>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-[#7C3F2B] hover:bg-[#633222] text-white font-semibold py-3 rounded-lg shadow-md transition duration-200">
                    Simpan Password Baru
                </button>
            </form>

        </div>
    </div>

    {{-- Script untuk tombol mata (lihat password) --}}
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>

</html>
