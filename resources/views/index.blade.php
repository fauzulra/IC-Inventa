@extends('layout.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 mb-6">
        <p class="text-sm text-gray-800 font-medium mb-5">Halo, Admin!</p>
        <h2 class="text-2xl font-bold text-gray-800">
            Selamat Datang di Sistem Inventaris<br>
            <span class="text-gray-800 text-lg">PT CIPTATAMA GRIYA PRIMA</span>
        </h2>

        <div class="mt-4 bg-yellow-400 border-l-4 border-yellow-400 p-4 rounded-lg">
            {{-- <div class="flex"> --}}
            {{-- <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div> --}}
            {{-- <div class=""> --}}
            <p class="text-sm font-semibold text-gray-800">Perhatian!</p>
            <p class="text-sm font-semibold text-gray-800">
                Pastikan seluruh data pada sistem selalu sesuai dengan kondisi aktual di gudang setiap harinya </p>
            {{-- </div> --}}
            {{-- </div> --}}
        </div>
    </div>
@endsection
