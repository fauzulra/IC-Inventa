<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inventa Cipta') }} - @yield('title')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    @yield('styles')
    <style>
        /* Animasi dari format Warkop */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-[#F0F0F0] font-sans antialiased flex flex-col h-screen overflow-hidden">

    <div class="flex-none z-50 border-b border-gray-300">
        @include('layout.header')
    </div>

    <div class="flex flex-1 overflow-hidden">

        <aside class="w-60 bg-[#F0F0F0] border-r-[3px] border-gray-300 flex-none hidden md:block">
            @include('layout.sidebar')
        </aside>

        <main class="flex-1 flex flex-col  overflow-x-hidden overflow-y-auto relative">

            <div class="w-full h-screen bg-cover bg-center bg-no-repeat relative"
                style="background-image: url('{{ asset('images/BG-Dashboard.jpg') }}');">
                <div class="p-6">
                    @yield('content')
                </div>
            </div>


        </main>
    </div>
    @yield('script')
</body>

</html>
