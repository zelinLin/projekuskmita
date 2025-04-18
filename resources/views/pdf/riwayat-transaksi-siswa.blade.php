<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi - {{ $siswa->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 6px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .status-approved {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Riwayat Transaksi</h2>
    <p>Nama: <strong>{{ $siswa->name }}</strong></p>
    <p>Email: {{ $siswa->email }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $trx->type)) }}</td>
                    <td>Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td>{{ $trx->receiver->name ?? '-' }}</td>
                    <td>
                        @if($trx->status === 'approved')
                            <span class="status-approved">Disetujui</span>
                        @elseif($trx->status === 'pending')
                            <span class="status-pending">Menunggu</span>
                        @elseif($trx->status === 'rejected')
                            <span class="status-rejected">Ditolak</span>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>{{ $trx->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
