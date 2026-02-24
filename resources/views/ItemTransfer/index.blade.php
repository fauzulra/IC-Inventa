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
                    @forelse ($transfers as $transfer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-3 text-center">{{ $loop->iteration }}</td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->material ? $transfer->material->name : 'Item Dihapus' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->quantity }} {{ $transfer->material ? $transfer->material->unit : '' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d/m/Y') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->toProject->name ? $transfer->toProject->name : 'Proyek Tidak Ditemukan' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @if ($transfer->status === 'completed')
                                    <button onclick="openStatusModal('{{ $transfer->material->name ?? '' }}')"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                        Selesai
                                    </button>
                                @elseif ($transfer->status === 'pending')
                                    <button onclick="openStatusModal('{{ $transfer->material->name ?? '' }}')"
                                        class="bg-orange-400 hover:bg-orange-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                        Dalam Proses
                                    </button>
                                @else
                                    <button onclick="openStatusModal('{{ $transfer->material->name ?? '' }}')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                        Ditolak
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada riwayat pengiriman barang.
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
                    <form action="{{ route('itemtransfer.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Item yang Dikirim</label>
                            <div class="relative">
                                <select name="material_id" required
                                    class="shadow border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white appearance-none">
                                    <option value="" disabled selected>Pilih Material...</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">{{ $material->name }} (Stok:
                                            {{ $material->stock }} {{ $material->unit }})</option>
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
                                <input type="number" name="quantity" placeholder="0" required min="1"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Satuan (Otomatis)</label>
                                <input type="text" placeholder="Mengikuti Item" disabled
                                    class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none cursor-not-allowed">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengiriman</label>
                            <input type="date" name="transfer_date" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Proyek Tujuan</label>
                            <div class="relative">
                                <select name="to_project_id" required
                                    class="shadow border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white appearance-none">

                                    <option value="" disabled selected>Pilih Proyek Tujuan...</option>

                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">
                                            {{ $project->code }} - {{ $project->name }}
                                        </option>
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
                                Kirim Barang
                            </button>
                            <button type="button" onclick="toggleModal('modalTransferBarang')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
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

@endsection

@section('script')
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
    @include('layout.script')
@endsection
