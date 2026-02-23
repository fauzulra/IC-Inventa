@extends('layout.app')

@section('title', 'Transfer Barang')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">

        {{-- <h2 class="text-xl font-semibold text-gray-800 mb-16">Kirim Baarang Antar Proyek</h2> --}}

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Kirim Barang Antar Proyek</h2>

        <button onclick="toggleModal('modalTransferBarang')"
            class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
            <i class="fas fa-plus"></i> Kirim Barang
        </button>

        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            <div class="text-gray-600">
                Show
                <select class="border border-gray-300 rounded px-2 py-1.5 mx-1 focus:outline-none focus:border-orange-400">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                entries
            </div>

            <div class="relative">
                <input type="text" placeholder="Cari Proyek"
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
                <tbody class="text-sm text-gray-600">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-200 px-4 py-3 text-center">1</td>
                        <td class="border border-gray-200 px-4 py-3">Triplek 9mm x 1,2m x 2,4m</td>
                        <td class="border border-gray-200 px-4 py-3">10</td>
                        <td class="border border-gray-200 px-4 py-3">01/02/2024</td>
                        <td class="border border-gray-200 px-4 py-3">Cipta Land</td>
                        <td class="border border-gray-200 px-4 py-3 text-center">
                            <button onclick="openStatusModal('Triplek 9mm x 1,2m x 2,4m')"
                                class="bg-orange-400 hover:bg-orange-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                Dalam Proses
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-200 px-4 py-3 text-center">2</td>
                        <td class="border border-gray-200 px-4 py-3">Semen 40kg</td>
                        <td class="border border-gray-200 px-4 py-3">50</td>
                        <td class="border border-gray-200 px-4 py-3">02/02/2024</td>
                        <td class="border border-gray-200 px-4 py-3">Cipta Grand City</td>
                        <td class="border border-gray-200 px-4 py-3 text-center">
                            <button onclick="openStatusModal('Semen 40kg')"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                Selesai
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-200 px-4 py-3 text-center">3</td>
                        <td class="border border-gray-200 px-4 py-3">Paku Kayu 3"</td>
                        <td class="border border-gray-200 px-4 py-3">20</td>
                        <td class="border border-gray-200 px-4 py-3">03/02/2024</td>
                        <td class="border border-gray-200 px-4 py-3">Cipta Residence</td>
                        <td class="border border-gray-200 px-4 py-3 text-center">
                            <button onclick="openStatusModal('Paku Kayu 3')"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                Selesai
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-center items-center mt-6 gap-2">
            <button
                class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Sebelum
            </button>
            <div class="flex gap-2">
                <button
                    class="bg-white border border-gray-300 text-gray-700 w-10 h-10 flex items-center justify-center rounded-md font-bold hover:bg-gray-50">1</button>
                <button
                    class="bg-white border border-gray-300 text-gray-700 w-10 h-10 flex items-center justify-center rounded-md font-bold hover:bg-gray-50">2</button>
            </div>
            <button
                class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 transition">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </button>
        </div>

    </div>

    <div id="modalTransferBarang" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalTransferBarang')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Form Pengiriman Barang</h3>
                        <button onclick="toggleModal('modalTransferBarang')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Item yang Dikirim</label>
                            <input type="text" placeholder="Contoh: Semen 40kg"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                <input type="number" placeholder="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                <input type="text" placeholder="Pcs / Kg"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengiriman</label>
                            <input type="date"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Proyek Tujuan</label>
                            <div class="relative">
                                <select
                                    class="shadow border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white appearance-none">
                                    <option>Pilih Proyek Tujuan...</option>
                                    <option>Cipta Land</option>
                                    <option>Cipta Grand City</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Kirim
                                Barang</button>
                            <button type="button" onclick="toggleModal('modalTransferBarang')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modalUpdateStatus" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div onclick="toggleModal('modalUpdateStatus')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Update Status Transfer</h3>
                        <button onclick="toggleModal('modalUpdateStatus')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Item Transfer</label>
                            <input type="text" id="statusItemName" readonly
                                class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-3">Pilih Status Terbaru:</label>
                            <div class="space-y-3">

                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-orange-50 transition group">
                                    <input type="radio" name="status_transfer" value="Berjalan"
                                        class="h-4 w-4 text-orange-400 focus:ring-orange-400 border-gray-300">
                                    <span class="ml-3 flex-1 block font-medium text-gray-700">Berjalan</span>
                                    <span
                                        class="bg-orange-400 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Proses</span>
                                </label>

                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition group">
                                    <input type="radio" name="status_transfer" value="Selesai"
                                        class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300">
                                    <span class="ml-3 flex-1 block font-medium text-gray-700">Selesai</span>
                                    <span
                                        class="bg-green-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Diterima</span>
                                </label>

                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-red-50 transition group">
                                    <input type="radio" name="status_transfer" value="Dibatalkan"
                                        class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300">
                                    <span class="ml-3 flex-1 block font-medium text-gray-700">Dibatalkan</span>
                                    <span
                                        class="bg-red-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Gagal</span>
                                </label>

                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Status
                            </button>
                            <button type="button" onclick="toggleModal('modalUpdateStatus')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Generic Toggle Modal
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }

        // Fungsi Khusus Membuka Modal Status & Mengisi Nama Item
        function openStatusModal(itemName) {
            // Isi nilai input readonly dengan nama item yang diklik
            document.getElementById('statusItemName').value = itemName;
            // Buka modal
            toggleModal('modalUpdateStatus');
        }
    </script>
@endsection
