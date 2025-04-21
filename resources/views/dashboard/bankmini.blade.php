<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Bank Mini</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="background-color: #f3f4f6;">

  <div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow mb-4">
      <div>
        <h2 class="h4 fw-semibold text-dark">Bank Mini</h2>
        <p class="text-muted small">Dashboard Bank Mini</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('riwayat.transaksi') }}" class="btn btn-sm" style="background-color: #ede9fe; color: #6b21a8;">Riwayat Transaksi</a>
        <form action="{{ url('/logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm" style="background-color: #fee2e2; color: #dc2626;">Logout</button>
        </form>
      </div>
    </div>

    <!-- Notifikasi -->
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <!-- Form Tambah User -->
    <div class="bg-white p-4 rounded shadow mb-4">
      <h5 class="mb-3 text-dark">Tambah User</h5>
      <form action="{{ route('admin.tambah.user') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label text-muted">Nama</label>
            <input type="text" name="name" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Email</label>
            <input type="email" name="email" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Password</label>
            <input type="password" name="password" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Role</label>
            <select name="role" class="form-select">
              <option value="siswa">Siswa</option>
            </select>
          </div>
        </div>
        <div class="text-end mt-4">
          <button type="submit" class="btn text-white" style="background-color: #7c3aed;">Tambah User</button>
        </div>
      </form>
    </div>

    <!-- Top-Up dan Tarik -->
    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="bg-white p-4 rounded shadow-sm">
          <h6 class="mb-3 text-dark">Top-Up Saldo</h6>
          <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="top_up" />
            <div class="mb-3">
              <label for="user_id_topup" class="form-label text-muted">User ID (Siswa)</label>
              <input type="number" name="user_id" id="user_id_topup" required class="form-control" />
            </div>
            <div class="mb-3">
              <label for="amount_topup" class="form-label text-muted">Jumlah</label>
              <input type="number" name="amount" id="amount_topup" min="1000" required class="form-control" />
            </div>
            <button type="submit" class="btn w-100 text-white" style="background-color: #2563eb;">Top-Up</button>
          </form>
        </div>
      </div>

      <div class="col-md-6">
        <div class="bg-white p-4 rounded shadow-sm">
          <h6 class="mb-3 text-dark">Tarik Saldo</h6>
          <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="withdraw" />
            <div class="mb-3">
              <label for="user_id_tarik" class="form-label text-muted">User ID (Siswa)</label>
              <input type="number" name="user_id" id="user_id_tarik" required class="form-control" />
            </div>
            <div class="mb-3">
              <label for="amount_tarik" class="form-label text-muted">Jumlah</label>
              <input type="number" name="amount" id="amount_tarik" min="1000" required class="form-control" />
            </div>
            <button type="submit" class="btn w-100 text-white" style="background-color: #dc2626;">Tarik Tunai</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Pending Transaksi -->
    <div class="bg-white p-4 rounded shadow mb-4">
      <h5 class="mb-3 text-dark">Transaksi Menunggu Persetujuan</h5>
      @php
        $pendingTransactions = \App\Models\Transaction::with('user')->where('status', 'pending')->latest()->get();
      @endphp
      @if ($pendingTransactions->isEmpty())
        <p class="text-muted small">Tidak ada transaksi pending.</p>
      @else
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pendingTransactions as $trx)
                <tr>
                  <td>{{ $trx->id }}</td>
                  <td>{{ $trx->user->name }}</td>
                  <td class="text-capitalize">{{ str_replace('_', ' ', $trx->type) }}</td>
                  <td>Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                  <td class="d-flex gap-2">
                    <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                      @csrf
                      <button class="btn btn-sm btn-success">Setujui</button>
                    </form>
                    <form action="{{ route('transaksi.reject', $trx->id) }}" method="POST">
                      @csrf
                      <button class="btn btn-sm btn-danger">Tolak</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <!-- Daftar Siswa -->
    <div class="bg-white p-4 rounded shadow mb-4">
      <h5 class="mb-3 text-dark">Daftar Siswa</h5>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>Rp {{ number_format($user->balance, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted small">Tidak ada data siswa</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
