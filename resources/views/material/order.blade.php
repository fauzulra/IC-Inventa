@extends('layout.app')

@section('title', 'Pemesanan Material Proyek')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Keterangan</th>
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
                                {{ ucwords($order->keterangan ?? '-') }}
                            </td>
                            <td class="border border-gray-200 px-4 py-3">
                                {{ ucwords($order->user) ? ucwords($order->user->name) : 'User Dihapus' }}</td>

                            {{-- Kolom Status --}}
                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @php
                                    $statusLow = strtolower($order->status);
                                    $badgeColor = 'bg-gray-400';

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
                                    <button
                                        onclick="openEditModal('{{ $order->id }}', '{{ addslashes($order->name) }}', '{{ $order->quantity }}', '{{ addslashes($order->unit) }}', '{{ \Carbon\Carbon::parse($order->request_date)->format('Y-m-d') }}', '{{ addslashes($order->keterangan) }}')"
                                        class="text-blue-500 hover:text-blue-700 mx-1" title="Edit Pesanan">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="openDeleteModal('{{ $order->id }}')"
                                        class="text-red-500 hover:text-red-700 mx-1" title="Batalkan Pesanan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="text-gray-400 italic">Dikunci</span>
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

    {{-- Modal Tambah Data Pesanan --}}
    <div id="modalTambahPesanan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalTambahPesanan')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Buat Pesanan Baru</h3>
                        <button type="button" onclick="toggleModal('modalTambahPesanan')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('material.order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Diajukan Oleh</label>
                            <div
                                class="cursor-not-allowed shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 font-medium leading-tight bg-gray-50">
                                {{ auth()->user()->name }} | {{ $project->code }} - {{ $project->name }}
                            </div>
                        </div>
                        <div id="order-items-container">
                            <div
                                class="order-item border border-blue-200 bg-blue-50 p-4 rounded-lg mb-4 relative transition-all">
                                <button type="button" onclick="removeOrderItem(this)"
                                    class="btn-remove-item hidden absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md focus:outline-none">
                                    <i class="fas fa-times text-xs"></i>
                                </button>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Item / Material</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="name[]" placeholder="Contoh: Triplek 9mm..." required
                                            class="input-name capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">

                                        <button type="button" onclick="openSearchModal(this)"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition duration-200 flex items-center gap-2 focus:outline-none">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex gap-4 mb-2">
                                    <div class="w-1/3">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                        <input type="number" name="quantity[]" placeholder="0" required min="1"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C]">
                                    </div>
                                    <div class="w-1/3">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                        <input type="text" name="unit[]" placeholder="Pcs/Kg/Lbr" required
                                            class="input-unit capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C]">
                                    </div>
                                    <div class="w-1/3">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tgl Pengajuan</label>
                                        <input type="date" name="request_date[]" required
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C]">
                                    </div>
                                </div>
                                <div class="mb-2 mt-3">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                                    <input type="text" name="keterangan[]" required
                                        placeholder="Contoh: Digunakan untuk area depan..."
                                        class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C]">
                                </div>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS (Bawah) --}}
                        <div class="flex flex-col sm:flex-row-reverse gap-2 border-t border-gray-100 pt-4 mt-2">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:w-auto sm:text-sm transition">
                                Kirim
                            </button>

                            {{-- Tombol Tambah Lagi dipindah ke sini --}}
                            <button type="button" onclick="addOrderItem()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:w-auto sm:text-sm transition">
                                <i class="fas fa-plus mr-2 mt-1"></i> Tambah Lagi
                            </button>

                            <button type="button" onclick="toggleModal('modalTambahPesanan')"
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition">
                                Batal
                            </button>
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
                class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">

                <div class="bg-gray-50 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Cari Master Material</h3>
                        <button type="button" onclick="toggleModal('modalCariMaterial')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-4 pb-4 sm:p-6">
                    <div class="relative mb-4">
                        <input type="text" id="searchMaterialInput" onkeyup="filterMaterialList()"
                            placeholder="Ketik nama material..."
                            class="shadow-sm border border-gray-300 rounded-md w-full py-2 pl-10 pr-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400"><i
                                class="fas fa-search"></i></div>
                    </div>

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
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PESANAN --}}
    <div id="modalEditPesanan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalEditPesanan')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Edit Pesanan</h3>
                        <button onclick="toggleModal('modalEditPesanan')" class="text-gray-400 hover:text-gray-500"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form id="formEditPesanan" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Item / Material</label>
                            <input type="text" id="edit_name" name="name" required
                                class="capitalize shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                <input type="number" id="edit_quantity" name="quantity" required min="1"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                <input type="text" id="edit_unit" name="unit" required
                                    class="capitalize shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengajuan</label>
                            <input type="date" id="edit_request_date" name="request_date" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                            <input type="text" id="edit_keterangan" name="keterangan" required
                                class="capitalize shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                            <button type="button" onclick="toggleModal('modalEditPesanan')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS PESANAN --}}
    <div id="modalDeletePesanan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalDeletePesanan')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Batalkan Pesanan</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin ingin membatalkan dan menghapus pesanan ini?
                    </p>
                    <form id="formDeletePesanan" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Ya,
                                Hapus</button>
                            <button type="button" onclick="toggleModal('modalDeletePesanan')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
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

        // Variabel untuk menyimpan baris item mana yang sedang dicari materialnya
        let activeOrderItem = null;

        function openSearchModal(btnElement) {
            activeOrderItem = btnElement.closest('.order-item');
            toggleModal('modalCariMaterial');
        }

        function pilihMaterial(name, unit) {
            if (activeOrderItem) {
                activeOrderItem.querySelector('.input-name').value = name;
                activeOrderItem.querySelector('.input-unit').value = unit;
            }
            toggleModal('modalCariMaterial');
        }

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

        function addOrderItem() {
            let container = document.getElementById('order-items-container');
            let itemsCount = container.querySelectorAll('.order-item').length;

            if (itemsCount >= 10) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal!',
                    text: 'Anda hanya bisa menambahkan maksimal 10 item dalam satu kali pengajuan.',
                    confirmButtonColor: '#FFB22C'
                });
                return;
            }

            let firstItem = container.querySelector('.order-item');
            let newItem = firstItem.cloneNode(true);

            let inputs = newItem.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.type !== 'date') {
                    input.value = '';
                }
            });

            newItem.querySelector('.btn-remove-item').classList.remove('hidden');
            newItem.classList.add('opacity-0');
            container.appendChild(newItem);

            setTimeout(() => {
                newItem.classList.remove('opacity-0');
            }, 50);
        }

        function removeOrderItem(btnElement) {
            let itemRow = btnElement.closest('.order-item');
            itemRow.remove();
        }

        // ==========================================
        // TANGKAP PESAN ALERT DARI CONTROLLER
        // ==========================================
        document.addEventListener("DOMContentLoaded", function() {

            // Tangkap pesan sukses
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!},
                    confirmButtonColor: '#28a745'
                });
            @endif

            // Tangkap error validasi form (misal belum diisi lengkap)
            @if ($errors->any())
                if (document.getElementById('modalTambahPesanan').classList.contains('hidden')) {
                    toggleModal('modalTambahPesanan'); // Buka lagi modalnya agar tahu yang salah
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    text: {!! json_encode($errors->first()) !!},
                    confirmButtonColor: '#d33'
                });
            @endif

            // Tangkap pesan error khusus
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: {!! json_encode(session('error')) !!},
                    confirmButtonColor: '#d33'
                });
            @endif

        });

        function openEditModal(id, name, quantity, unit, requestDate, keterangan) {
            // Set URL Action
            document.getElementById('formEditPesanan').action = `/material/order/${id}`;

            // Set Value Form
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_quantity').value = quantity;
            document.getElementById('edit_unit').value = unit;
            document.getElementById('edit_request_date').value = requestDate;
            document.getElementById('edit_keterangan').value = keterangan;

            toggleModal('modalEditPesanan');
        }

        function openDeleteModal(id) {
            document.getElementById('formDeletePesanan').action = `/material/order/${id}`;
            toggleModal('modalDeletePesanan');
        }
    </script>
    @include('layout.script')
@endsection
