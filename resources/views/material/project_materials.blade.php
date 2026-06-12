@extends('layout.app')

@section('title', 'Data Material Proyek')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

        @if (auth()->user()->hasRole('admin'))
            <div class="flex flex-col md:flex-row justify-between mb-20 gap-4">
                <div class="flex justify-start">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Material - {{ $project->name }}</h2>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('material.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition font-medium">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- <button onclick="toggleModal('modalTambahMaterial')"
                class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus"></i> Tambah Material
            </button> --}}
        @else
            <div class="flex flex-col md:flex-row justify-between items-center mb-20 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Material - {{ $project->name }}</h2>
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    {{-- Tambahkan parameter ?browse=true agar controller tahu ini klik dari tombol --}}
                    <a href="{{ route('material.index', ['browse' => 'true']) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition font-medium flex items-center gap-2">
                        <i class="fas fa-eye"></i> Lihat Proyek Lain
                    </a>
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 w-12 text-center">NO
                        </th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Item</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Kuantitas</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Satuan</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Pemasok</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($materials as $material)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-1.5 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-200 px-4 py-1.5">{{ ucwords($material->name) }}</td>
                            <td class="border border-gray-200 px-4 py-1.5 ">{{ $material->pivot->stock }}</td>
                            <td class="border border-gray-200 px-4 py-1.5">{{ ucwords($material->unit) }}</td>
                            <td class="border border-gray-200 px-4 py-1.5">
                                {{ $material->supplier ? ucwords($material->supplier->name) : '-' }}
                            </td>
                            <td class="border border-gray-200 px-4 py-1.5 text-center">
                                @if (auth()->user()->hasRole('admin'))
                                    <div class="flex justify-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <button
                                            onclick="openEditModal('{{ $material->id }}', '{{ $material->name }}', '{{ $material->pivot->stock }}', '{{ $material->unit }}', '{{ $material->supplier_id }}')"
                                            class="text-orange-400 border border-orange-400 hover:bg-orange-50 rounded p-1 w-8 h-8 flex items-center justify-center transition">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <button onclick="openDeleteModal('{{ $material->id }}')"
                                            class="text-red-500 border border-red-500 hover:bg-red-50 rounded p-1 w-8 h-8 flex items-center justify-center transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    {{-- Tampilan Dikunci untuk Non-Admin --}}
                                    <span
                                        class="text-gray-400 italic text-xs flex items-center justify-center gap-1 cursor-not-allowed">
                                        <i class="fas fa-lock text-[10px]"></i> Dikunci
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada data material untuk proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH MATERIAL (Hanya dirender jika Admin) --}}
    @if (auth()->user()->hasRole('admin'))
        <div id="modalTambahMaterial" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div onclick="toggleModal('modalTambahMaterial')"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Tambah Material ke {{ $project->name }}
                            </h3>
                            <button onclick="toggleModal('modalTambahMaterial')"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <form action="{{ route('material.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Item / Material</label>
                                <input type="text" name="name" placeholder="Contoh: Triplek 9mm..." required
                                    class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="flex gap-4 mb-4">
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas Awal</label>
                                    <input type="number" name="stock" value="0" readonly
                                        class="bg-gray-100 cursor-not-allowed shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                    <input type="text" name="unit" placeholder="Pcs / Kg / Lembar" required
                                        class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pemasok (Supplier)</label>
                                <div class="relative">
                                    <select name="supplier_id"
                                        class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                        <option value="" disabled selected>Pilih Pemasok...</option>
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
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                <button type="button" onclick="toggleModal('modalTambahMaterial')"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT MATERIAL --}}
        <div id="modalEditMaterial" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div onclick="toggleModal('modalEditMaterial')"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Edit Data Material</h3>
                            <button onclick="toggleModal('modalEditMaterial')"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <form id="editMaterialForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Item / Material</label>
                                <input type="text" id="edit_name" name="name" required
                                    class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="flex gap-4 mb-4">
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                    <input type="number" id="edit_stock" name="stock" readonly
                                        class="bg-gray-100 shadow cursor-not-allowed appearance-none border rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                    <input type="text" id="edit_unit" name="unit" required
                                        class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pemasok (Supplier)</label>
                                <div class="relative">
                                    <select id="edit_supplier_id" name="supplier_id" required
                                        class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                        <option value="" disabled>Pilih Pemasok...</option>
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
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                                <button type="button" onclick="toggleModal('modalEditMaterial')"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DELETE MATERIAL --}}
        <div id="modalDeleteMaterial" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div onclick="toggleModal('modalDeleteMaterial')"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Keluarkan dari Proyek</h3>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin ingin menghapus data material ini dari
                            proyek <b>{{ $project->name }}</b>?</p>
                        <form id="deleteMaterialForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="flex flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                                <button type="button" onclick="toggleModal('modalDeleteMaterial')"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
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
            const modal = document.getElementById(modalID);
            if (modal) modal.classList.toggle("hidden");
        }

        function openEditModal(id, name, stock, unit, supplierId) {
            let form = document.getElementById('editMaterialForm');
            if (form) {
                form.action = `/material/${id}`;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_stock').value = stock;
                document.getElementById('edit_unit').value = unit;
                document.getElementById('edit_supplier_id').value = supplierId;
                toggleModal('modalEditMaterial');
            }
        }

        function openDeleteModal(id) {
            let form = document.getElementById('deleteMaterialForm');
            if (form) {
                form.action = `/material/${id}`;
                toggleModal('modalDeleteMaterial');
            }
        }
    </script>
    @include('layout.script')
@endsection
