@extends('layout.app')

@section('content')
    <div class="flex min-h-screen bg-gray-100">

        <!-- Sidebar -->
        <aside class="w-64 bg-white text-white flex flex-col ">
            <!-- Logo -->
            <div class="p-1 ">
                <div class="flex items-center ">
                    <img src="{{ asset('images/logo-sidebar.png') }}" class="mx-auto h-21.25" alt="">
                </div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 pb-4">
                <div class="px-4 mb-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Menu Utama</span>
                </div>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 bg-gray-300 border-l-4 border-yellow-400">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Proyek
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Material Item
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Pemasok
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Barang Masuk
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Barang Keluar
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Transfer Barang
                </a>

                <a href=""
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Pemesanan
                </a>
            </nav>


            {{-- @include('layout.sidebar') --}}

            <!-- User Prosfile -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center">
                        <span class="text-grey-700 font-semibold">Iqbal</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-400 font-semibold">Iqbal</p>
                        <p class="text-xs text-gray-400">Admin</p>
                    </div>
                </div>
                <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center mt-3 px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar
                </a>
                <form id="logout-form" action="" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Header -->
            <header class="{{-- bg-white shadow-sm h-21.25 --}} flex items-center justify-between h-16 px-2 sm:px-6 lg:px-8 ">
                <div class="{{-- px-6 py-6 --}} flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Dashboard ADMIN</h1>
                </div>
            </header>

            <!-- Content Area -->
            <div class="">
                <!-- Welcome Card -->
                <div class="bg-white rounded-lg shadow-md border-2 border-blue-400 p-6 mb-6">
                    <p class="text-sm text-gray-600 mb-2">Halo, Admin!</p>
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        Selamat Datang di Aplikasi Sistem Inventaris Barang<br>
                        PT CIPTATAMA GRIYA PRIMA
                    </h2>

                    <!-- Warning Box -->
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded">
                        <p class="text-sm font-semibold text-yellow-800">Perhatian!</p>
                        <p class="text-sm text-yellow-700">
                            Pastikan seluruh data pada sistem selalu sesuai dengan kondisi aktual di gudang setiap harinya
                        </p>
                    </div>
                </div>

                <!-- Illustration -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <div class="inline-block mb-4">
                                <!-- Illustration placeholder -->
                                <svg class="w-64 h-64 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                                </svg>
                            </div>
                            <h3 class="text-4xl font-bold text-gray-500 mb-2">INVENTA CIPTA</h3>
                            <p class="text-gray-400 uppercase tracking-wide">I See My Inventory</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
