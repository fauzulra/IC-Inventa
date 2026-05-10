<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Keluar - {{ $projectName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            text-align: center;
            width: 200px;
        }

        .signature p {
            margin-bottom: 60px;
        }

        @media print {
            @page {
                margin: 1cm;
                size: landscape;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN PENGELUARAN BARANG (BARANG KELUAR)</h2>
        <h2>INVENTA CIPTA</h2>
        <p>Proyek Asal: <strong>{{ strtoupper($projectName) }}</strong></p>
        <p>Periode: {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal Keluar</th>
                <th width="25%">Nama Material</th>
                <th width="15%">Kuantitas</th>
                <th width="25%">Dikirim Ke / Proyek Tujuan</th>
                @if ($projectName == 'Semua Proyek')
                    <th width="15%">Dari Proyek</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($outgoingGoods as $outgoing)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($outgoing->date_shipped)->format('d/m/Y') }}</td>
                    <td>{{ $outgoing->material ? $outgoing->material->name : '-' }}</td>
                    <td class="text-center">{{ $outgoing->quantity }}
                        {{ $outgoing->material ? $outgoing->material->unit : '' }}</td>

                    <td>
                        @if ($outgoing->destinationProject)
                            {{ $outgoing->destinationProject->code }} - {{ $outgoing->destinationProject->name }}
                        @else
                            Pemakaian Internal
                        @endif
                    </td>

                    @if ($projectName == 'Semua Proyek')
                        <td>{{ $outgoing->sourceProject ? $outgoing->sourceProject->name : '-' }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $projectName == 'Semua Proyek' ? 6 : 5 }}" class="text-center">
                        <em>Tidak ada transaksi pengeluaran barang pada periode ini.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Batam, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <br><br><br>
            <p><strong>{{ auth()->user()->name }}</strong></p>
            <p style="margin-top: -5px; font-size: 10px;">
                {{ strtoupper(str_replace('_', ' ', auth()->user()->roles->first()->name)) }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
