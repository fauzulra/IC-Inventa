@extends('layout.app')

@section('title', 'Pilih Proyek')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200 mt-6">

        <!-- Judul dinamis (Bisa berubah jadi Data Master, Pemesanan, dll) -->
        <h2 class="text-xl font-bold text-gray-800 mb-6">{{ $title }}</h2>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Pilih Proyek</label>

            <div class="border border-gray-300 rounded-md overflow-hidden">
                @forelse ($projects as $project)
                    <!-- Link dinamis sesuai target menu yang diklik Admin -->
                    <a href="{{ route($targetRoute, $project->id) }}"
                        class="block px-4 py-3 bg-white border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out text-sm text-gray-700 font-medium">
                        {{ $project->name }}
                    </a>
                @empty
                    <div class="px-4 py-3 bg-white text-sm text-gray-500 text-center">
                        Belum ada data proyek.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center mt-8 gap-2">
            @if ($projects->onFirstPage())
                <button disabled
                    class="bg-[#854d3d] opacity-50 cursor-not-allowed text-white px-4 py-1.5 rounded-md font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Sebelum
                </button>
            @else
                <a href="{{ $projects->previousPageUrl() }}"
                    class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-1.5 rounded-md font-medium text-sm flex items-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i> Sebelum
                </a>
            @endif

            <div class="flex gap-2">
                @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="{{ $page == $projects->currentPage() ? 'border-[#854d3d] text-[#854d3d]' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }} border px-3 py-1 rounded-md text-sm font-medium transition">
                        {{ $page }}
                    </a>
                @endforeach
            </div>

            @if ($projects->hasMorePages())
                <a href="{{ $projects->nextPageUrl() }}"
                    class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-1.5 rounded-md font-medium text-sm flex items-center gap-2 transition">
                    Selanjutnya <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <button disabled
                    class="bg-[#854d3d] opacity-50 cursor-not-allowed text-white px-4 py-1.5 rounded-md font-medium text-sm flex items-center gap-2">
                    Selanjutnya <i class="fas fa-arrow-right"></i>
                </button>
            @endif
        </div>

    </div>
@endsection
