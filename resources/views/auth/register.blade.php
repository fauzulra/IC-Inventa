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
        <div class="bg-white rounded-2xl shadow-xl w-full sm:p-10 border border-gray-100 flex flex-col md:flex-row ">
            <div class="w-full md:w-5/12 flex flex-col justify-center mb-auto pr-0 md:pr-4">
                <h2 class="text-3xl font-bold text-gray-900 ">
                    Registrasi
                </h2>
                <p class="text-sm text-gray-500 mb-8 pt-2">
                    Harap lengkapi untuk membuat akun Anda
                </p>

                <div class="flex flex-col items-center justify-center mt-10">
                    <img src="{{ asset('images/logo.png') }}" alt="Ilustrasi Registrasi"
                        class="w-full max-w-70 object-contain drop-shadow-lg">

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-700 font-medium drop-shadow-sm">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                                class="text-[#0065F8] hover:text-blue-800 hover:underline transition ml-1">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-7/12 mt-10 md:mt-0">
                <form action="{{ route('register') }}" method="POST" class="space-y-2">
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
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" required value="{{ old('username') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                        @error('username')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">No HP</label>
                        <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                        @error('phone')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Daftar
                            Sebagai</label>
                        <div class="relative">
                            <select name="role" id="role" required
                                class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white transition">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran...
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
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
                        @error('role')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-48 bg-[#7C3F2B] hover:bg-[#633222] text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200">
                            Daftar
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

</body>

</html>
