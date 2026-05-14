@extends('layout.app')

@section('title', 'Proyek')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">

        @if (auth()->user()->hasRole('admin'))
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Proyek PT CIPTATAMA GRIYA PRIMA</h2>

            <button onclick="toggleModal('modalTambahProyek')"
                class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus"></i> Tambah Proyek
            </button>
        @else
            <h2 class="text-xl font-semibold text-gray-800 mb-20">Daftar Proyek PT CIPTATAMA GRIYA PRIMA</h2>
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
                <input type="text" id="searchInput" placeholder="Cari Proyek"
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">ID Proyek</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Nama Proyek</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Lokasi</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($projects as $project)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-1.5 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-200 px-4 py-1.5">{{ $project->code }}</td>
                            <td class="border border-gray-200 px-4 py-1.5">{{ $project->name }}</td>
                            <td class="border border-gray-200 px-4 py-1.5">{{ $project->location }}</td>

                            {{-- Kolom Status --}}
                            <td class="border border-gray-200 px-4 py-1.5 text-center">
                                @if (auth()->user()->hasRole('admin'))
                                    {{-- Bisa diklik Admin --}}
                                    <button onclick="openStatusModal('{{ $project->name }}')"
                                        class="{{ $project->status === 'selesai' ? 'bg-green-500 hover:bg-green-600' : 'bg-orange-400 hover:bg-orange-500' }} text-white px-3 py-1.5 rounded-md text-xs font-semibold cursor-pointer transition transform hover:scale-105 shadow-sm">
                                        {{ ucfirst($project->status) }}
                                    </button>
                                @else
                                    {{-- Terkunci untuk User Biasa --}}
                                    <span
                                        class="bg-gray-100 text-gray-400 border border-gray-200 px-3 py-1.5 rounded-md text-xs font-semibold flex items-center justify-center gap-1 cursor-not-allowed">
                                        <i class="fas fa-lock text-[10px]"></i> {{ ucfirst($project->status) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="border border-gray-200 px-4 py-1.5 text-center">
                                @if (auth()->user()->hasRole('admin'))
                                    <div class="flex justify-center gap-2">
                                        <button
                                            onclick="openEditModal('{{ $project->id }}', '{{ $project->name }}', '{{ $project->location }}', '{{ $project->logistics_contact }}')"
                                            class="text-orange-400 border border-orange-400 hover:bg-orange-50 rounded p-1 w-8 h-8 flex items-center justify-center transition">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="openDeleteModal('{{ $project->id }}')"
                                            class="text-red-500 border border-red-500 hover:bg-red-50 rounded p-1 w-8 h-8 flex items-center justify-center transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-xs flex items-center justify-center gap-1">
                                        <i class="fas fa-lock text-[10px]"></i> Dikunci
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                Belum ada data proyek.
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

    {{-- Modal Tambah Proyek --}}
    <div id="modalTambahProyek" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalTambahProyek')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Tambah Data Proyek</h3>
                        <button onclick="toggleModal('modalTambahProyek')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('project.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Proyek</label>
                            <input type="text" name="name" placeholder="Contoh: Cipta Piayu Village" required
                                class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                            <input type="text" name="location" placeholder="Contoh: Piayu" required
                                class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" onclick="toggleModal('modalTambahProyek')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update Status Proyek --}}
    <div id="modalUpdateStatusProyek" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalUpdateStatusProyek')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Update Status Proyek</h3>
                        <button onclick="toggleModal('modalUpdateStatusProyek')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Proyek</label>
                            <input type="text" id="statusProyekName" readonly
                                class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-3">Pilih Status:</label>
                            <div class="space-y-3">
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-orange-50 transition group">
                                    <input type="radio" name="status_proyek" value="Berjalan"
                                        class="h-4 w-4 text-orange-400 focus:ring-orange-400 border-gray-300">
                                    <span class="ml-3 flex-1 block font-medium text-gray-700">Berjalan</span>
                                    <span
                                        class="bg-orange-400 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Aktif</span>
                                </label>
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition group">
                                    <input type="radio" name="status_proyek" value="Selesai"
                                        class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300">
                                    <span class="ml-3 flex-1 block font-medium text-gray-700">Selesai</span>
                                    <span
                                        class="bg-green-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold">Selesai</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan
                                Status</button>
                            <button type="button" onclick="toggleModal('modalUpdateStatusProyek')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PROYEK --}}
    <div id="modalEditProyek" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalEditProyek')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Edit Data Proyek</h3>
                        <button onclick="toggleModal('modalEditProyek')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form id="editProjectForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Proyek</label>
                            <input type="text" id="edit_name" name="name" required
                                class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                            <input type="text" id="edit_location" name="location" required
                                class="capitalize shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Logistik (Nama & Kontak)</label>
                            <div class="relative">
                                <select id="edit_logistics_contact" name="logistics_contact" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled>Pilih Logistik...</option>
                                    <option value="Seno – 0813 6421 9203">Seno – 0813 6421 9203</option>
                                    <option value="Ryan Sinaga – 0858 3735 0411">Ryan Sinaga – 0858 3735 0411</option>
                                    <option value="Dhuha – 0895 6036 81241">Dhuha – 0895 6036 81241</option>
                                    <option value="Retno Diaz – 0813 6411 9665">Retno Diaz – 0813 6411 9665</option>
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
                            <button type="button" onclick="toggleModal('modalEditProyek')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE PROYEK --}}
    <div id="modalDeleteProyek" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalDeleteProyek')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Konfirmasi Hapus</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin ingin menghapus data proyek ini? Tindakan ini
                        tidak dapat dibatalkan.</p>
                    <form id="deleteProjectForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                            <button type="button" onclick="toggleModal('modalDeleteProyek')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
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

        function openStatusModal(proyekName) {
            document.getElementById('statusProyekName').value = proyekName;
            toggleModal('modalUpdateStatusProyek');
        }

        function openEditModal(id, name, location, contact) {
            let form = document.getElementById('editProjectForm');
            form.action = `/project/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_logistics_contact').value = contact;
            toggleModal('modalEditProyek');
        }

        function openDeleteModal(id) {
            let form = document.getElementById('deleteProjectForm');
            form.action = `/project/${id}`;
            toggleModal('modalDeleteProyek');
        }
    </script>
    @include('layout.script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
