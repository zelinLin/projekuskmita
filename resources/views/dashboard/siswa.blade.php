<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <!-- Header -->
    <div class="bg-white rounded-3 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Selamat datang, {{ Auth::user()->name }}</h4>
                <small class="text-muted">Dashboard Siswa</small>
            </div>
            <form action="{{ url('/logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none">Logout</button>
            </form>
        </div>

        <!-- Saldo -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Saldo Anda</h6>
                        <h3 class="text-primary">
                            Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Transaksi -->
        <div class="row mb-4">
            <!-- Top-Up -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Top-Up Saldo</h5>
                        <form action="{{ route('siswa.topup') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="jumlah_topup" class="form-label">Jumlah Top-Up</label>
                                <input type="number" name="jumlah" id="jumlah_topup" required class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Top-Up</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tarik -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Tarik Saldo</h5>
                        <form action="{{ route('siswa.tarik') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="withdraw">
                            <div class="mb-3">
                                <label for="jumlah_tarik" class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah_tarik" required class="form-control">
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Tarik</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Transfer -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Transfer Saldo</h5>
                        <form action="{{ route('transfer.saldo') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="target_siswa" class="form-label">Nama Tujuan</label>
                                <input type="text" name="target_siswa" id="target_siswa" list="daftar_siswa" class="form-control" required>
                                <datalist id="daftar_siswa">
                                    @if (isset($users) && $users->count() > 0)
                                        @foreach ($users as $user)
                                            <option value="{{ $user->name }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah_transfer" class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah_transfer" min="1000" required class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success w-100">Transfer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0 fw-bold">Riwayat Transaksi</h5>
                                <a href="{{ route('siswa.cetak.riwayat') }}" target="_blank" class="btn btn-danger btn-sm">
                                    <i class="bi bi-printer me-1"></i> Cetak PDF
                                </a>
                            </div>                        
                        <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nomor</th>
                                <th>Jenis Transaksi</th>
                                <th>Jumlah</th>
                                <th>Nama Penerima</th>
                                <th>Tanggal</th>
                                <th>Status</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $transaction)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($transaction->type == 'top_up')
                                        <span class="badge bg-success">Top-up</span>
                                    @elseif($transaction->type == 'withdraw')
                                        <span class="badge bg-danger">Tarik</span>
                                    @elseif($transaction->type == 'transfer')
                                        <span class="badge bg-warning text-dark">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>{{ $transaction->receiver ? $transaction->receiver->name : '-' }}</td>
                                <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($transaction->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($transaction->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($transaction->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        

    </div> <!-- End .bg-white -->
</div> <!-- End .container -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
