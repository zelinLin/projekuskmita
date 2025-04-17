<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi Semua Pengguna</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Riwayat Transaksi</h2>
    <p>Dicetak oleh: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role }})</p>
    <p>Tanggal: {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pengirim</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Penerima</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($trx->type) }}</td>
                    <td>Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td>{{ $trx->receiver->name ?? '-' }}</td>
                    <td>{{ $trx->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
