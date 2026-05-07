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
                            <input type="text" name="username" id="username"
                                class="focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border">
                        </div>
                    </div>

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
                            <input type="password" name="password" id="password"
                                class="focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3 border">
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-[#0065F8] hover:text-blue-800 underline">
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
                    <div class="flex items-center justify-center">
                        <div class="text-sm">
                            <span class="font-medium text-gray-600">Belum punya akun?</span>
                            <a href="{{ route('register') }}"
                                class="font-medium text-[#0065F8] hover:text-blue-800 underline ">
                                Daftar disini
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
