@extends('layout.app')

@section('title', 'Daftar Barang Masuk')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">

        {{-- <h2 class="text-xl font-semibold text-gray-800 mb-16">Daftar Barang Masuk PT CIPTATAMA GRIYA PRIMA</h2> --}}

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Barang Masuk PT CIPTATAMA GRIYA PRIMA</h2>

        <button onclick="toggleModal('modalTambahBarangMasuk')"
            class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
            <i class="fas fa-plus"></i> Tambah Barang Masuk
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
                <input type="text" placeholder="Cari Barang"
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Tanggal Masuk</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Pemasok</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-4 text-center">1</td>
                        <td class="border border-gray-200 px-4 py-4">Triplek 9mm x 1,2m x 2,4m</td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4">Taman Niaga Sukses</td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-4 text-center">2</td>
                        <td class="border border-gray-200 px-4 py-4">Semen 40kg</td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4">Taman Niaga Sukses</td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-4 text-center">3</td>
                        <td class="border border-gray-200 px-4 py-4">Paku Kayu 3"</td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4">Taman Niaga Sukses</td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-4 text-center">4</td>
                        <td class="border border-gray-200 px-4 py-4">Paku Kayu 4"</td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4">Taman Niaga Sukses</td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-4 text-center">5</td>
                        <td class="border border-gray-200 px-4 py-4">Paku Kayu 1 1/2"</td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4">Taman Niaga Sukses</td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-4 py-4 text-center">6</td>
                        <td class="border border-gray-200 px-4 py-4">Paku Rivet 4mm, tinggi kepala 1cm (1 box = 500pcs)
                        </td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4"></td>
                        <td class="border border-gray-200 px-4 py-4">Taman Niaga Sukses</td>
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
                <button
                    class="bg-white border border-gray-300 text-gray-700 w-10 h-10 flex items-center justify-center rounded-md font-bold hover:bg-gray-50">3</button>
            </div>
            <button
                class="bg-[#854d3d] hover:bg-[#6b3d31] text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 transition">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </button>
        </div>

    </div>

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
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            Input Barang Masuk
                        </h3>
                        <button onclick="toggleModal('modalTambahBarangMasuk')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Item</label>
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
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                            <input type="date"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pemasok</label>

                            <div class="relative">
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Pilih Pemasok...</option>
                                    <option value="Taman Niaga Sukses">Taman Niaga Sukses</option>
                                    <option value="Toko Besi Jaya">Toko Besi Jaya</option>
                                    <option value="Sumber Rejeki">Sumber Rejeki</option>
                                    <option value="Mitra Abadi">Mitra Abadi</option>
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
                                Simpan
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

    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }
    </script>
@endsection
