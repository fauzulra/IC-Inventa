@extends('layout.app')

@section('title', 'Pemesanan Transfer Barang')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengajuan Transfer Barang Antar Proyek</h2>

        <button onclick="toggleModal('modalTambahPesanan')"
            class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
            <i class="fas fa-plus"></i> Buat Pengajuan Transfer
        </button>

        {{-- Show & Search --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            <div class="text-gray-600">
                Show
                <select id="showEntries"
                    class="border border-gray-300 rounded px-2 py-1.5 mx-1 focus:outline-none focus:border-orange-400">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                entries
            </div>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari Barang"
                    class="border border-gray-300 rounded pl-3 pr-10 py-1.5 focus:outline-none focus:border-orange-400 w-64">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 w-12 text-center">ID
                        </th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Material</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Kuantitas</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Tgl Transfer</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Proyek Asal</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Proyek Tujuan</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    {{-- Loop data dari variabel $transfers --}}
                    @forelse ($transfers as $transfer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-3 text-center">{{ $loop->iteration }}</td>

                            <td class="border border-gray-200 px-4 py-3 font-medium text-gray-900">
                                {{ $transfer->material ? $transfer->material->name : 'Material Dihapus' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->quantity }} {{ $transfer->material ? $transfer->material->unit : '' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d/m/Y') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->fromProject ? $transfer->fromProject->name : '-' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->toProject ? $transfer->toProject->name : '-' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @if (strtolower($transfer->status) == 'pending')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pending</span>
                                @elseif(strtolower($transfer->status) == 'selesai' || strtolower($transfer->status) == 'diterima')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Selesai</span>
                                @elseif(strtolower($transfer->status) == 'dibatalkan' || strtolower($transfer->status) == 'ditolak')
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Dibatalkan</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ ucfirst($transfer->status) }}</span>
                                @endif
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @if (strtolower($transfer->status) == 'pending')
                                    <button class="text-blue-500 hover:text-blue-700 mx-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700 mx-1" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs italic">Dikunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada pengajuan transfer barang antar proyek.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center items-center mt-6 gap-2">
            <button id="prevBtn"
                class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Sebelum
            </button>
            <div id="pageNumbers" class="flex gap-2"></div>
            <button id="nextBtn"
                class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 transition">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </button>
        </div>

    </div>

    {{-- Modal Tambah Pengajuan Transfer --}}
    <div id="modalTambahPesanan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalTambahPesanan')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            Buat Pengajuan Transfer
                        </h3>
                        <button onclick="toggleModal('modalTambahPesanan')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('itemtransfer.order.store') }}" method="POST">
                        @csrf

                        <!-- PILIH MATERIAL -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Material</label>
                            <div class="relative">
                                <select name="material_id" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Pilih Material dari Database...</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }} (Stok saat ini: {{ $material->stock }}
                                            {{ $material->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('material_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KUANTITAS & TANGGAL -->
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                <input type="number" name="quantity" placeholder="0" required min="1"
                                    value="{{ old('quantity') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                                @error('quantity')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transfer</label>
                                <input type="date" name="transfer_date" required value="{{ old('transfer_date') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                                @error('transfer_date')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- PROYEK ASAL -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Proyek Asal (Pengirim)</label>
                            <div class="relative">
                                <select name="from_project_id" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Pilih Proyek Asal...</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('from_project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->code }} - {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('from_project_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PROYEK TUJUAN -->
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Proyek Tujuan (Penerima)</label>
                            <div class="relative">
                                <select name="to_project_id" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Pilih Proyek Tujuan...</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('to_project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->code }} - {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('to_project_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Ajukan Transfer
                            </button>
                            <button type="button" onclick="toggleModal('modalTambahPesanan')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }
    </script>
    @include('layout.script')
@endsection
