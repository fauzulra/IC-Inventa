@extends('layout.app')

@section('title', 'Daftar Barang Masuk')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">


        {{-- HEADER UNTUK LOGISTIK --}}
        @if (auth()->user()->hasRole('logistik'))
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Barang Masuk - {{ $project->name }}</h2>
                </div>
            </div>
            <div class="flex gap-2 mb-6">
                <button onclick="toggleModal('modalTambahBarangMasuk')"
                    class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md flex items-center gap-2 transition duration-200">
                    <i class="fas fa-plus"></i> Input Barang Masuk
                </button>
                <button onclick="toggleModal('modalCetakLaporan')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm py-3 px-4 rounded-md flex items-center gap-2 transition duration-200">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
            </div>

            {{-- HEADER UNTUK ADMIN --}}
        @elseif (auth()->user()->hasRole('admin'))
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Barang Masuk - {{ $project->name }}</h2>
                </div>
                <div class="flex justify-end w-full md:w-auto gap-2">
                    <button onclick="toggleModal('modalCetakLaporan')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm py-2 px-4 rounded-md flex items-center gap-2 transition duration-200">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </button>
                    <a href="{{ route('incominggood.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition font-medium flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- HEADER UNTUK STAFF --}}
        @else
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Barang Masuk - {{ $project->name }}</h2>
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    <button onclick="toggleModal('modalCetakLaporan')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm py-2 px-4 rounded-md flex items-center gap-2 transition duration-200">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </button>
                </div>
            </div>
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Material</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Kuantitas Masuk</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Tanggal Masuk</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Sumber / Pemasok</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($incomingGoods as $incoming)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-4 text-center">{{ $loop->iteration }}</td>

                            <td class="border border-gray-200 px-4 py-4 font-medium text-gray-900">
                                {{ $incoming->material ? $incoming->material->name : 'Item Dihapus' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-4 font-bold text-green-600">
                                +{{ $incoming->quantity }} {{ $incoming->material ? $incoming->material->unit : '' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-4">
                                {{ \Carbon\Carbon::parse($incoming->date_received)->format('d/m/Y') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-4">
                                {{ $incoming->supplier ? $incoming->supplier->name : 'Dari Transfer Proyek / Lainnya' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada data penerimaan barang di proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH BARANG MASUK --}}
    <div id="modalTambahBarangMasuk" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div onclick="toggleModal('modalTambahBarangMasuk')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Input Penerimaan Barang</h3>
                        <button onclick="toggleModal('modalTambahBarangMasuk')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('incominggood.store') }}" method="POST">
                        @csrf

                        <!-- HIDDEN PROJECT ID -->
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <!-- 1. PILIH PESANAN YANG DATANG -->
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <label class="block text-blue-800 text-sm font-bold mb-2">Berdasarkan Surat Pesanan:</label>
                            <div class="relative">
                                <select name="order_id" id="order_id"
                                    class="shadow appearance-none border border-blue-300 rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                    <option value="" selected>-- Pilih Pesanan yang Telah Dikonfirmasi --</option>
                                    @if (isset($approvedOrders))
                                        @foreach ($approvedOrders as $order)
                                            <!-- Kita simpan data kuantitas di atribut 'data-qty' agar bisa dibaca JS -->
                                            <option value="{{ $order->id }}" data-qty="{{ $order->quantity }}">
                                                {{ $order->name }} (Pesan: {{ $order->quantity }} {{ $order->unit }}) -
                                                Tgl: {{ \Carbon\Carbon::parse($order->request_date)->format('d/m/Y') }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <p class="text-xs text-blue-600 mt-1 italic">Hanya pesanan berstatus 'Selesai' yang muncul di
                                sini.</p>
                        </div>

                        <!-- 2. HUBUNGKAN KE MASTER MATERIAL -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Simpan ke Master Material</label>
                            <div class="relative">
                                <select name="material_id" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Cocokkan pesanan dengan data Master Material...
                                    </option>
                                    @foreach ($materials as $material)
                                        {{-- LOGIKA BARU: Tampilkan stok saat ini dari tabel Pivot --}}
                                        <option value="{{ $material->id }}">
                                            {{ $material->name }} (Stok saat ini: {{ $material->pivot->stock }}
                                            {{ $material->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- 3. DETAIL PENERIMAAN -->
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas Diterima</label>
                                <input type="number" id="quantity_input" name="quantity" placeholder="0" required
                                    min="1" value="{{ old('quantity') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Diterima</label>
                                <input type="date" name="date_received" required
                                    value="{{ old('date_received') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sumber Barang (Pemasok)</label>
                            <div class="relative">
                                <select name="supplier_id"
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" selected>-- Dari Transfer Proyek / Lainnya --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Penerimaan
                            </button>
                            <button type="button" onclick="toggleModal('modalTambahBarangMasuk')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CETAK LAPORAN --}}
    <div id="modalCetakLaporan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalCetakLaporan')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Cetak Laporan Barang Masuk
                        </h3>
                        <button onclick="toggleModal('modalCetakLaporan')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    {{-- Form menggunakan GET dan target="_blank" agar buka di Tab Baru --}}
                    <form action="{{ route('incominggood.report') }}" method="GET" target="_blank">

                        @if (auth()->user()->hasRole('admin'))
                            {{-- TAMPILAN ADMIN: Bisa pilih proyek --}}
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Proyek</label>
                                <select name="project_id"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="all">-- Semua Proyek --</option>
                                    @foreach ($allProjects as $proj)
                                        <option value="{{ $proj->id }}"
                                            {{ $project->id == $proj->id ? 'selected' : '' }}>
                                            {{ $proj->code }} - {{ $proj->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{-- TAMPILAN LOGISTIK/STAFF: Proyek terkunci --}}
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Proyek</label>
                                <input type="text" disabled value="{{ $project->name }}"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-500 bg-gray-100 cursor-not-allowed">
                                {{-- Kita tetap mengirim project_id di belakang layar meski controller sudah mem-bypass-nya --}}
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                            </div>
                        @endif

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Dari Tanggal</label>
                                <input type="date" name="start_date" required
                                    value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sampai Tanggal</label>
                                <input type="date" name="end_date" required
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                        </div>

                        <div class="flex flex-row-reverse mt-2">
                            <button type="submit" onclick="toggleModal('modalCetakLaporan')"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                <i class="fas fa-print mr-2 mt-1"></i> Cetak Laporan
                            </button>
                            <button type="button" onclick="toggleModal('modalCetakLaporan')"
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

        // Script untuk mengisi kuantitas otomatis saat pesanan dipilih
        document.getElementById('order_id').addEventListener('change', function() {
            // Ambil opsi yang sedang dipilih
            let selectedOption = this.options[this.selectedIndex];

            // Ambil data-qty dari opsi tersebut
            let qty = selectedOption.getAttribute('data-qty');

            // Jika ada isinya, masukkan ke input kuantitas
            if (qty) {
                document.getElementById('quantity_input').value = qty;
            } else {
                document.getElementById('quantity_input').value = '';
            }
        });
    </script>
    @include('layout.script')
@endsection
