<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Transaksi Harian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi Harian</h1>
                <p class="text-gray-600 text-sm">Laporan transaksi harian dengan detail informasi transaksi.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('siswa.cetak.riwayat') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-sm text-gray-700 rounded hover:bg-gray-100 transition">
                    <i class="bi bi-printer"></i> Cetak Riwayat
                </a>
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-sm text-gray-700 rounded hover:bg-gray-100 transition">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Tabel Riwayat Transaksi -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Tambahan kolom di bagian <thead> -->
<thead class="bg-purple-600 text-white text-sm uppercase">
    <tr>
        <th class="px-6 py-3 text-left font-semibold">No</th>
        <th class="px-6 py-3 text-left font-semibold">Nama</th>
        <th class="px-6 py-3 text-left font-semibold">Jenis Transaksi</th>
        <th class="px-6 py-3 text-left font-semibold">Jumlah</th>
        <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
        <th class="px-6 py-3 text-left font-semibold">Nama Penerima</th>
        <th class="px-6 py-3 text-left font-semibold">Status</th> <!-- Kolom baru -->
    </tr>
</thead>

                <!-- Tambahan kolom di bagian <tbody> -->
@forelse($transactions->sortByDesc('created_at') as $index => $transaction)
<tr class="hover:bg-gray-50 text-sm">
    <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
    <td class="px-6 py-4 text-gray-900">{{ $transaction->user->name }}</td>
    <td class="px-6 py-4">
        @if($transaction->type == 'top_up')
            <span class="bg-green-100 text-green-800 px-2 py-1 text-xs font-semibold rounded-full">Top-Up</span>
        @elseif($transaction->type == 'withdraw')
            <span class="bg-red-100 text-red-800 px-2 py-1 text-xs font-semibold rounded-full">Tarik Tunai</span>
        @elseif($transaction->type == 'transfer')
            <span class="bg-blue-100 text-blue-800 px-2 py-1 text-xs font-semibold rounded-full">Transfer</span>
        @else
            <span class="bg-gray-100 text-gray-800 px-2 py-1 text-xs font-semibold rounded-full">Lainnya</span>
        @endif
    </td>
    <td class="px-6 py-4 text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
    <td class="px-6 py-4 text-gray-500">{{ $transaction->created_at->format('d-m-Y') }}</td>
    <td class="px-6 py-4 text-gray-900">
        {{ $transaction->receiver ? $transaction->receiver->name : '-' }}
    </td>
    <td class="px-6 py-4">
        @if($transaction->status == 'approved')
            <span class="bg-green-100 text-green-800 px-2 py-1 text-xs font-semibold rounded-full">Disetujui</span>
        @elseif($transaction->status == 'rejected')
            <span class="bg-red-100 text-red-800 px-2 py-1 text-xs font-semibold rounded-full">Ditolak</span>
        @elseif($transaction->status == 'pending')
            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs font-semibold rounded-full">Menunggu</span>
        @else
            <span class="bg-gray-100 text-gray-800 px-2 py-1 text-xs font-semibold rounded-full">{{ ucfirst($transaction->status) }}</span>
        @endif
    </td>
    
      
</tr>
@empty
<tr>
    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data transaksi</td>
</tr>
@endforelse

            </table>
        </div>
    </div>
</body>
</html>
