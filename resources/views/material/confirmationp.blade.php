@extends('layout.app')

@section('title', 'Konfirmasi Pesanan Material')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">

        <h2 class="text-xl font-semibold text-gray-800 mb-16">Konfirmasi Pesanan Material</h2>

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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Proyek Tujuan</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-200 px-4 py-3 font-medium text-gray-900">{{ $order->name }}</td>

                            {{-- Unit sekarang langsung menempel pada model Order --}}
                            <td class="border border-gray-200 px-4 py-3">{{ $order->quantity }} {{ $order->unit }}</td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ \Carbon\Carbon::parse($order->request_date)->format('d/m/Y') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $order->project ? $order->project->name : '-' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @php
                                    // Logika warna badge yang lebih dinamis
                                    $statusLow = strtolower($order->status);
                                    $btnColor = 'bg-gray-400 hover:bg-gray-500'; // Fallback

                                    if ($statusLow == 'pending') {
                                        $btnColor = 'bg-yellow-400 hover:bg-yellow-500';
                                    } elseif ($statusLow == 'berjalan') {
                                        $btnColor = 'bg-blue-400 hover:bg-blue-500';
                                    } elseif ($statusLow == 'selesai') {
                                        $btnColor = 'bg-green-500 hover:bg-green-600';
                                    } elseif ($statusLow == 'dibatalkan') {
                                        $btnColor = 'bg-red-500 hover:bg-red-600';
                                    }
                                @endphp

                                <button onclick="openStatusModal('{{ $order->id }}', '{{ $order->name }}')"
                                    class="{{ $btnColor }} text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                    {{ ucfirst($order->status) }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500 border border-gray-200">Belum ada data
                                pesanan yang perlu dikonfirmasi.</td>
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

    {{-- MODAL KONFIRMASI STATUS --}}
    <div id="modalKonfirmasiStatus" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div onclick="closeStatusModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Update Status Pesanan
                        </h3>
                        <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form id="statusForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Item yang diupdate</label>
                            <input type="text" id="modalItemName" value="" readonly
                                class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none font-medium">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-3">Pilih Status Baru:</label>

                            <div class="space-y-3">
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition group">
                                    <input type="radio" name="status" value="Berjalan" required
                                        class="h-4 w-4 text-blue-400 focus:ring-blue-400 border-gray-300">
                                    <span
                                        class="ml-3 flex-1 block font-medium text-gray-700 group-hover:text-blue-600">Berjalan</span>
                                    <span
                                        class="bg-blue-400 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Proses</span>
                                </label>

                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition group">
                                    <input type="radio" name="status" value="Selesai" required
                                        class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300">
                                    <span
                                        class="ml-3 flex-1 block font-medium text-gray-700 group-hover:text-green-600">Selesai</span>
                                    <span
                                        class="bg-green-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Sukses</span>
                                </label>

                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-red-50 transition group">
                                    <input type="radio" name="status" value="Dibatalkan" required
                                        class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300">
                                    <span
                                        class="ml-3 flex-1 block font-medium text-gray-700 group-hover:text-red-600">Dibatalkan</span>
                                    <span
                                        class="bg-red-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Gagal</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeStatusModal()"
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
        // JS Sekarang menerima orderId dan itemName
        function openStatusModal(orderId, itemName) {
            // 1. Isi nama item ke input readonly di modal
            document.getElementById('modalItemName').value = itemName;

            // 2. PERUBAHAN: Ubah URL form action sesuai dengan route material.confirmation.update
            document.getElementById('statusForm').action = "/material/confirmation/" + orderId;

            // 3. Tampilkan modal
            document.getElementById('modalKonfirmasiStatus').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('modalKonfirmasiStatus').classList.add('hidden');
        }
    </script>
    @include('layout.script')
@endsection
