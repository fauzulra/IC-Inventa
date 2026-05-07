@extends('layout.app')

@section('title', 'Histori Global Transfer Barang')

@section('content')
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 mt-6">

        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800">Riwayat Keseluruhan Transfer Antar Proyek</h2>
            <p class="text-sm text-gray-500 mt-1">Laporan semua aktivitas perpindahan material</p>
        </div>

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
                <input type="text" id="searchInput" placeholder="Cari Barang / Proyek"
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
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Proyek Asal</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700">Proyek Tujuan</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-sm text-gray-600">
                    @forelse ($transfers as $transfer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border border-gray-200 px-4 py-3 text-center">{{ $loop->iteration }}</td>

                            <td class="border border-gray-200 px-4 py-3 font-medium text-gray-900">
                                {{ $transfer->material ? $transfer->material->name : 'Material Dihapus' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 font-bold text-orange-600">
                                {{ $transfer->quantity }} {{ $transfer->material ? $transfer->material->unit : '' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3">
                                {{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d/m/Y') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 font-medium text-red-600">
                                {{ $transfer->fromProject ? $transfer->fromProject->name : '-' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 font-medium text-green-600">
                                {{ $transfer->toProject ? $transfer->toProject->name : '-' }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">
                                @php
                                    $statusLow = strtolower($transfer->status);
                                    $badgeColor = 'bg-gray-400';

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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 border border-gray-200">
                                Belum ada riwayat transfer barang yang tercatat di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('script')
    @include('layout.script')
@endsection
