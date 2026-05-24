@extends('layout.app')

@section('title', 'Pengajuan Transfer Barang')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

        {{-- Header & Tombol Kembali --}}
        @if (auth()->user()->hasRole('logistik'))
            {{-- Tampilan Logistik --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Pengajuan Transfer - {{ $project->name }}</h2>
                </div>
            </div>

            <button onclick="toggleModal('modalTambahPesanan')"
                class="bg-[#FFB22C] hover:bg-orange-500 text-white font-medium text-sm py-3 px-4 rounded-md mb-6 flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus"></i> Buat Pengajuan Transfer
            </button>
        @elseif (auth()->user()->hasRole('admin'))
            {{-- Tampilan Admin --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-20 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Pengajuan Transfer - {{ $project->name }}</h2>
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    <a href="{{ route('itemtransfer.order') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition font-medium flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        @else
            {{-- Tampilan Staf (Tanpa Tombol) --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-20 gap-4">
                <div class="flex justify-start w-full md:w-auto">
                    <h2 class="text-xl font-bold text-gray-800">Pengajuan Transfer - {{ $project->name }}</h2>
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Kuantitas</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Tgl Transfer</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Diminta dari</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($transfers as $transfer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-3 text-center">{{ $loop->iteration }}</td>

                            <td class="border border-gray-200 px-4 py-3 font-medium text-gray-900">
                                {{ ucwords($transfer->material ? $transfer->material->name : 'Material Dihapus') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 font-bold text-orange-600">
                                {{ $transfer->quantity }} {{ $transfer->material ? $transfer->material->unit : '' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d/m/Y') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ $transfer->fromProject ? $transfer->fromProject->name : '-' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @php
                                    $statusLow = strtolower($transfer->status);
                                    $badgeColor = 'bg-gray-400'; // Default abu-abu

                                    if ($statusLow == 'pending') {
                                        $badgeColor = 'bg-yellow-400';
                                    } elseif (in_array($statusLow, ['berjalan', 'dikirim'])) {
                                        $badgeColor = 'bg-blue-400';
                                    } elseif (in_array($statusLow, ['selesai', 'diterima'])) {
                                        $badgeColor = 'bg-green-500';
                                    } elseif (in_array($statusLow, ['dibatalkan', 'ditolak'])) {
                                        $badgeColor = 'bg-red-500';
                                    }
                                @endphp

                                <span
                                    class="{{ $badgeColor }} text-white px-3 py-1.5 rounded-md text-xs font-semibold shadow-sm inline-block">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @if ($statusLow == 'pending')
                                    <button
                                        onclick="openEditModal({{ $transfer->id }}, {{ $transfer->from_project_id }}, {{ $transfer->material_id }}, {{ $transfer->quantity }}, '{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('Y-m-d') }}')"
                                        class="text-blue-500 hover:text-blue-700 mx-1 transition transform hover:scale-110"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $transfer->id }})"
                                        class="text-red-500 hover:text-red-700 mx-1 transition transform hover:scale-110"
                                        title="Batalkan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs italic">Dikunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-gray-200 px-4 py-6 text-center text-gray-500">
                                Belum ada pengajuan transfer barang dari proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Modal Tambah Pengajuan (Permintaan) Transfer --}}
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
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Minta Barang ke Proyek Lain
                        </h3>
                        <button onclick="toggleModal('modalTambahPesanan')"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form action="{{ route('itemtransfer.order.store') }}" method="POST">
                        @csrf

                        <!-- LOGIKA DIBALIK: Proyek Tujuan (Penerima) otomatis adalah proyek yang sedang dibuka -->
                        <input type="hidden" name="to_project_id" value="{{ $project->id }}">

                        <!-- PROYEK ASAL (DIMINTA DARI MANA) -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Minta Bantuan Dari Proyek:</label>
                            <div class="relative">
                                <select name="from_project_id" id="from_project_id" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Pilih Proyek Asal...</option>
                                    @if (isset($allProjects))
                                        @foreach ($allProjects as $proj)
                                            @if ($proj->id != $project->id)
                                                <option value="{{ $proj->id }}"
                                                    {{ old('from_project_id') == $proj->id ? 'selected' : '' }}>
                                                    {{ $proj->code }} - {{ $proj->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 italic">Pilih proyek mana yang ingin Anda mintai barang.
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Material</label>
                            <div class="relative">
                                <!-- Tambahkan id="material_id" agar mudah dipanggil oleh JavaScript -->
                                <select name="material_id" id="material_id" required
                                    class="shadow appearance-none border rounded w-full py-2 pl-3 pr-10 text-gray-700 leading-normal focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent bg-white">
                                    <option value="" disabled selected>Pilih Proyek Asal terlebih dahulu...</option>
                                    {{-- Opsi akan diisi otomatis oleh JavaScript --}}
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas Permintaan</label>
                                <input type="number" name="quantity" placeholder="0" required min="1"
                                    value="{{ old('quantity') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Target Tgl Diterima</label>
                                <input type="date" name="transfer_date" required value="{{ old('transfer_date') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FFB22C] focus:border-transparent">
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FFB22C] text-base font-medium text-white hover:bg-orange-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Ajukan Permintaan
                            </button>
                            <button type="button" onclick="toggleModal('modalTambahPesanan')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PENGAJUAN TRANSFER --}}
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
                        <h3 class="text-lg font-bold text-gray-900">Edit Permintaan Transfer</h3>
                        <button onclick="toggleModal('modalEditPesanan')" class="text-gray-400 hover:text-gray-500"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form id="formEditPesanan" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Minta Dari Proyek:</label>
                            <select name="from_project_id" id="edit_from_project_id" required
                                onchange="fetchMaterialsForEdit(this.value)"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                <option value="" disabled>Pilih Proyek Asal...</option>
                                @if (isset($allProjects))
                                    @foreach ($allProjects as $proj)
                                        @if ($proj->id != $project->id)
                                            <option value="{{ $proj->id }}">{{ $proj->code }} -
                                                {{ $proj->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Material</label>
                            <select name="material_id" id="edit_material_id" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-white">
                                <option value="" disabled selected>Memuat material...</option>
                            </select>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kuantitas</label>
                                <input type="number" name="quantity" id="edit_quantity" required min="1"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Target Tanggal</label>
                                <input type="date" name="transfer_date" id="edit_transfer_date" required
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                        </div>

                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Update
                                Permintaan</button>
                            <button type="button" onclick="toggleModal('modalEditPesanan')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS/BATAL TRANSFER --}}
    <div id="modalDeletePesanan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="toggleModal('modalDeletePesanan')"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Batalkan Pengajuan Transfer</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin ingin membatalkan pengajuan transfer barang
                        ini?</p>
                    <form id="formDeletePesanan" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Ya,
                                Batalkan</button>
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

        // ==============================================================
        // LOGIKA DYNAMIC DROPDOWN (AJAX FETCH MATERIAL)
        // ==============================================================
        document.getElementById('from_project_id').addEventListener('change', function() {
            let projectId = this.value;
            let materialSelect = document.getElementById('material_id');

            // 1. Tampilkan status loading
            materialSelect.innerHTML = '<option value="" disabled selected>Memuat data material...</option>';

            // 2. Lakukan request ke server (Controller)
            fetch(`/itemtransfer/project/${projectId}/materials`)
                .then(response => response.json())
                .then(data => {
                    // 3. Bersihkan dropdown
                    materialSelect.innerHTML = '<option value="" disabled selected>Pilih Material...</option>';

                    // 4. Jika proyek tidak punya material sama sekali
                    if (data.length === 0) {
                        materialSelect.innerHTML +=
                            '<option value="" disabled>Proyek ini tidak memiliki stok material.</option>';
                        return;
                    }

                    // 5. Isi dropdown dengan data material dan stoknya
                    data.forEach(material => {
                        // material.pivot.stock berisi jumlah stok yang ada di proyek tersebut
                        materialSelect.innerHTML += `
                            <option value="${material.id}">
                                ${material.name} (Sisa Stok: ${material.pivot.stock} ${material.unit})
                            </option>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error fetching materials:', error);
                    materialSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data.</option>';
                });
        });

        function openEditModal(id, fromProjectId, materialId, quantity, transferDate) {
            // Set action URL form
            document.getElementById('formEditPesanan').action = `/itemtransfer/order/${id}`;

            // Set value input biasa
            document.getElementById('edit_from_project_id').value = fromProjectId;
            document.getElementById('edit_quantity').value = quantity;
            document.getElementById('edit_transfer_date').value = transferDate;

            // Karena material_id adalah dropdown yang di-fetch secara AJAX, kita panggil fungsinya khusus
            fetchMaterialsForEdit(fromProjectId, materialId);

            toggleModal('modalEditPesanan');
        }

        function openDeleteModal(id) {
            document.getElementById('formDeletePesanan').action = `/itemtransfer/order/${id}`;
            toggleModal('modalDeletePesanan');
        }

        // Fungsi fetch material khusus untuk modal EDIT (Mirip dengan AJAX Tambah Data)
        function fetchMaterialsForEdit(projectId, selectedMaterialId = null) {
            let materialSelect = document.getElementById('edit_material_id');
            materialSelect.innerHTML = '<option value="" disabled selected>Memuat data material...</option>';

            fetch(`/itemtransfer/project/${projectId}/materials`)
                .then(response => response.json())
                .then(data => {
                    materialSelect.innerHTML = '<option value="" disabled>Pilih Material...</option>';
                    if (data.length === 0) {
                        materialSelect.innerHTML +=
                            '<option value="" disabled>Proyek ini tidak memiliki stok material.</option>';
                        return;
                    }
                    data.forEach(material => {
                        // Cek apakah material ini yang sedang tersimpan sebelumnya
                        let isSelected = (selectedMaterialId && material.id == selectedMaterialId) ?
                            'selected' : '';
                        materialSelect.innerHTML += `
                            <option value="${material.id}" ${isSelected}>
                                ${material.name} (Sisa Stok: ${material.pivot.stock} ${material.unit})
                            </option>
                        `;
                    });
                })
                .catch(error => {
                    materialSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data.</option>';
                });
        }
        document.addEventListener("DOMContentLoaded", function() {
            // ... (kode sukses lainnya) ...

            // Jika ada pesan error dari controller (termasuk validasi stok)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            @endif
        });

        document.addEventListener("DOMContentLoaded", function() {
            // MENANGKAP SEMUA ERROR VALIDASI
            @if ($errors->any())
                // Jika validasi gagal, buka kembali modal yang bersangkutan
                // Ganti 'modalTambahPesanan' sesuai ID modal di halaman tersebut
                if (typeof toggleModal !== 'undefined') {
                    toggleModal('modalTambahPesanan');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Valid!',
                    // Menampilkan pesan error pertama dari daftar error
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
    @include('layout.script')
@endsection

{{-- @section('script')
    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
        }
    </script>
    @include('layout.script')
@endsection --}}
