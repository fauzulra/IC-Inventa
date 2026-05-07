@extends('layout.app')

@section('title', 'Daftar Barang Keluar')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

        {{-- ============================================== --}}
        {{-- HEADER BERDASARKAN ROLE PENGGUNA               --}}
        {{-- ============================================== --}}
        @if (auth()->user()->hasRole('logistik'))
            {{-- Tampilan Logistik --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Barang Keluar - {{ $project->name }}</h2>
                </div>
            </div>
            <button onclick="toggleModal('modalTambahBarangKeluar')"
                class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus"></i> Tambah Barang Keluar
            </button>
        @elseif (auth()->user()->hasRole('admin'))
            {{-- Tampilan Admin --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-20 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Barang Keluar - {{ $project->name }}</h2>
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    <a href="{{ route('outgoinggood.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition font-medium flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        @else
            {{-- TAMPILAN STAF (Tanpa Tombol Tambah, Tanpa Tombol Kembali) --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-20 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Barang Keluar - {{ $project->name }}</h2>
                </div>
            </div>
        @endif

        {{-- ============================================== --}}
        {{-- TABEL DATA                                     --}}
        {{-- ============================================== --}}
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 w-12 text-center">NO
                        </th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Item</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Kuantitas</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Tanggal Keluar</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Proyek Tujuan</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($outgoingGoods as $outgoing)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-4 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-200 px-4 py-4 font-medium text-gray-900">
                                {{ $outgoing->material ? $outgoing->material->name : 'Item Dihapus' }}
                            </td>
                            <td class="border border-gray-200 px-4 py-4 font-bold text-red-600">
                                -{{ $outgoing->quantity }} {{ $outgoing->material ? $outgoing->material->unit : '' }}
                            </td>
                            <td class="border border-gray-200 px-4 py-4">
                                {{ date('d/m/Y', strtotime($outgoing->date_shipped)) }}
                            </td>
                            <td class="border border-gray-200 px-4 py-4">
                                @if ($outgoing->destinationProject)
                                    {{ $outgoing->destinationProject->code }} - {{ $outgoing->destinationProject->name }}
                                @else
                                    <span class="text-gray-400 italic">Tanpa Proyek</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada data barang keluar di proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================== --}}
    {{-- MODAL TAMBAH BARANG KELUAR (KHUSUS LOGISTIK)   --}}
    {{-- ============================================== --}}
    @if (auth()->user()->hasRole('logistik'))
        <div id="modalTambahBarangKeluar" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div onclick="toggleModal('modalTambahBarangKeluar')"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Input Barang Keluar</h3>
                            <button onclick="toggleModal('modalTambahBarangKeluar')"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <form action="{{ route('outgoinggood.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="source_project_id" value="{{ $project->id }}">

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Item yang Dikeluarkan</label>
                                <div class="relative">
                                    <select name="material_id" id="material_id" required
                                        class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                        <option value="" disabled selected>Pilih Item / Material...</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}" data-unit="{{ $material->unit }}"
                                                data-stock="{{ $material->pivot->stock }}">
                                                {{ $material->name }} (Sisa Stok: {{ $material->pivot->stock }}
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

                            <div class="flex gap-4 mb-4">
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                    <input type="number" name="quantity" id="quantity_input" placeholder="0" required
                                        min="1"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                                    <p id="stockHelpText" class="text-xs text-red-500 mt-1 hidden">Kuantitas melebihi stok!
                                    </p>
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                    <input type="text" id="unit_display" placeholder="Mengikuti Item" disabled
                                        class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none cursor-not-allowed">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Keluar</label>
                                <input type="date" name="date_shipped" required
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Proyek Tujuan</label>
                                <div class="relative">
                                    <select name="destination_project_id" required
                                        class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                        <option value="" disabled selected>Pilih Proyek Tujuan...</option>
                                        @foreach ($projects as $prj)
                                            {{-- HAPUS @if DI SINI, TAMPILKAN SEMUA PROYEK TERMASUK PROYEK SENDIRI --}}
                                            <option value="{{ $prj->id }}">{{ $prj->code }} -
                                                {{ $prj->name }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-row-reverse">
                                <button type="submit" id="submitBtn"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan Keluaran
                                </button>
                                <button type="button" onclick="toggleModal('modalTambahBarangKeluar')"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }

        // Script hanya berjalan jika elemen ada (artinya user adalah logistik)
        const materialSelect = document.getElementById('material_id');
        const unitDisplay = document.getElementById('unit_display');
        const quantityInput = document.getElementById('quantity_input');
        const stockHelpText = document.getElementById('stockHelpText');
        const submitBtn = document.getElementById('submitBtn');

        if (materialSelect) {
            materialSelect.addEventListener('change', function() {
                let selectedOption = this.options[this.selectedIndex];
                let unit = selectedOption.getAttribute('data-unit');
                let maxStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

                unitDisplay.value = unit ? unit : '';
                quantityInput.max = maxStock;
                quantityInput.value = '';

                checkQuantity();
            });
        }

        function checkQuantity() {
            let maxStock = parseInt(quantityInput.max) || 0;
            let currentInput = parseInt(quantityInput.value) || 0;

            if (currentInput > maxStock) {
                stockHelpText.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                stockHelpText.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', checkQuantity);
        }
    </script>
    @include('layout.script')
@endsection
