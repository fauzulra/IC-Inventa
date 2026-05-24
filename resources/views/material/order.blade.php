@extends('layout.app')

@section('title', 'Pemesanan Material Proyek')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

        {{-- Header & Tombol Kembali (Hanya untuk Admin) --}}
        {{-- Header & Tombol Kembali --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex justify-start w-full md:w-auto">
                <h2 class="text-xl font-bold text-gray-800">Pemesanan Material - {{ $project->name }}</h2>
            </div>

            @if (auth()->user()->hasRole('admin'))
                <div class="flex justify-end w-full md:w-auto">
                    <a href="{{ route('material.order') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition font-medium flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            @endif
        </div>

        {{-- HANYA STAFF YANG BISA MELIHAT TOMBOL INI --}}
        @if (auth()->user()->hasRole('staff') || auth()->user()->hasRole('staf_lapangan'))
            <button onclick="toggleModal('modalTambahPesanan')"
                class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus"></i> Tambah Pesanan
            </button>
        @endif

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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Item</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Kuantitas</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Tanggal Kirim</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Diajukan Oleh</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-200 px-4 py-3 ">{{ ucwords($order->name) }}</td>
                            <td class="border border-gray-200 px-4 py-3  ">{{ $order->quantity }}
                                {{ ucwords($order->unit) }}</td>
                            <td class="border border-gray-200 px-4 py-3">
                                {{ \Carbon\Carbon::parse($order->request_date)->format('d/m/Y') }}</td>
                            <td class="border border-gray-200 px-4 py-3">
                                {{ ucwords($order->user) ? ucwords($order->user->name) : 'User Dihapus' }}</td>

                            {{-- Kolom Status --}}
                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @php
                                    $statusLow = strtolower($order->status);
                                    $badgeColor = 'bg-gray-400'; // Fallback warna abu-abu

                                    if ($statusLow == 'pending') {
                                        $badgeColor = 'bg-yellow-400';
                                    } elseif ($statusLow == 'berjalan') {
                                        $badgeColor = 'bg-blue-400';
                                    } elseif ($statusLow == 'diterima' || $statusLow == 'selesai') {
                                        $badgeColor = 'bg-green-500';
                                    } elseif ($statusLow == 'ditolak' || $statusLow == 'dibatalkan') {
                                        $badgeColor = 'bg-red-500';
                                    }
                                @endphp

                                <span
                                    class="{{ $badgeColor }} text-white px-3 py-1.5 rounded-md text-xs font-semibold shadow-sm inline-block">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @if (strtolower($order->status) == 'pending')
                                    <button class="text-blue-500 hover:text-blue-700 mx-1" title="Edit Pesanan"><i
                                            class="fas fa-edit"></i></button>
                                    <button class="text-red-500 hover:text-red-700 mx-1" title="Batalkan Pesanan"><i
                                            class="fas fa-trash"></i></button>
                                @else
                                    <span class="text-gray-400  italic">Dikunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada data pesanan yang diajukan untuk proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Modal Tambah Data --}}
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
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Buat Pesanan Baru</h3>
                        <button onclick="toggleModal('modalTambahPesanan')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('material.order.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Item / Material</label>
                            <div class="flex gap-2">
                                <input type="text" name="name" id="input_material_name"
                                    placeholder="Contoh: Triplek 9mm..." required value="{{ old('name') }}"
                                    class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">

                                <button type="button" onclick="toggleModal('modalCariMaterial')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition duration-200 flex items-center gap-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                <input type="number" name="quantity" placeholder="0" required min="1"
                                    value="{{ old('quantity') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                <input type="text" name="unit" placeholder="Pcs / Kg / Lbr" required
                                    value="{{ old('unit') }}"
                                    class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengajuan</label>
                            <input type="date" name="request_date" required value="{{ old('request_date') }}"
                                class="shadow appearance-none border rounded w-full sm:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Diajukan Oleh</label>

                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                            <div
                                class=" cursor-not-allowed shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 font-medium leading-tight break-words min-h-[40px]">
                                {{ auth()->user()->name }} | {{ $project->code }} - {{ $project->name }}
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Kirim</button>
                            <button type="button" onclick="toggleModal('modalTambahPesanan')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CARI MATERIAL --}}
    <div id="modalCariMaterial" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalCariMaterial')"
                class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">

                <div class="bg-gray-50 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Cari Master Material</h3>
                        <button type="button" onclick="toggleModal('modalCariMaterial')"
                            class="text-gray-400 hover:text-gray-500"><i class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-4 pb-4 sm:p-6">
                    {{-- Input Pencarian --}}
                    <div class="relative mb-4">
                        <input type="text" id="searchMaterialInput" onkeyup="filterMaterialList()"
                            placeholder="Ketik nama material..."
                            class="shadow-sm border border-gray-300 rounded-md w-full py-2 pl-10 pr-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>

                    {{-- Daftar Material --}}
                    <div class="max-h-60 overflow-y-auto custom-scrollbar border border-gray-100 rounded-md">
                        <ul id="materialList" class="divide-y divide-gray-100">
                            @if (isset($materials) && $materials->count() > 0)
                                @foreach ($materials as $mat)
                                    <li class="material-item">
                                        <button type="button"
                                            onclick="pilihMaterial('{{ $mat->name }}', '{{ $mat->unit }}')"
                                            class="w-full text-left px-4 py-3 hover:bg-blue-50 transition focus:outline-none focus:bg-blue-50 group">
                                            <div class="font-medium text-gray-800 group-hover:text-blue-700">
                                                {{ ucwords($mat->name) }}</div>
                                            <div class="text-xs text-gray-500">Satuan: {{ ucwords($mat->unit) }}</div>
                                        </button>
                                    </li>
                                @endforeach
                            @else
                                <li class="px-4 py-3 text-sm text-gray-500 text-center">Belum ada master material.</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse">
                    <button type="button" onclick="toggleModal('modalCariMaterial')"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
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

        // Fungsi untuk memilih material dan mengisi input form
        function pilihMaterial(name, unit) {
            document.getElementById('input_material_name').value = name;
            document.getElementById('input_material_unit').value = unit;

            // Tutup modal pencarian
            toggleModal('modalCariMaterial');
        }

        // Fungsi untuk memfilter list material saat diketik (Live Search)
        function filterMaterialList() {
            let input = document.getElementById("searchMaterialInput").value.toLowerCase();
            let items = document.querySelectorAll(".material-item");

            items.forEach(function(item) {
                let text = item.innerText.toLowerCase();
                if (text.includes(input)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        }
    </script>
    @include('layout.script')
@endsection
