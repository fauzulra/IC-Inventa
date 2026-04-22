<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Inventa Cipta</title>

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
                Lupa Password
            </h2>

            <p class="text-sm text-gray-600 text-center mb-8">
                Silakan masukkan alamat email yang terdaftar pada akun Anda.
            </p>

            <form action="" method="POST">
                @csrf

                <div class="mb-8 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600">
                        <i class="far fa-envelope text-lg"></i>
                    </div>
                    <input type="email" name="email" required
                        class="w-full pl-10 pr-3 py-3 border border-gray-500 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                        placeholder="">
                </div>

                <button type="submit"
                    class="w-full bg-[#7C3F2B] hover:bg-[#633222] text-white font-semibold py-3 rounded-lg shadow-md transition duration-200">
                    Kirim
                </button>

                <div class="mt-5 text-center">
                    <a href="#" class="text-sm font-medium text-[#0065F8] hover:text-blue-800 underline">
                        Kembali ke Halaman Login
                    </a>
                </div>
            </form>

        </div>
    </div>

</body>

</html>
