<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="bg-white rounded-3 shadow-sm p-4">
      
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-1">Selamat datang, Admin</h4>
          <small class="text-muted">Dashboard Admin</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('riwayat.transaksi') }}" class="btn btn-outline-primary btn-sm">Riwayat Transaksi</a>
          <form action="{{ url('/logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
          </form>
        </div>
      </div>

      <!-- Notifikasi -->
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <!-- Form Tambah User -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title mb-3">Tambah User</h5>
          <form action="{{ route('admin.tambah.user') }}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required />
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required />
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
              </div>
              <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-select">
                  <option value="admin">Admin</option>
                  <option value="bank_mini">Bank Mini</option>
                  <option value="siswa">Siswa</option>
                </select>
              </div>
            </div>
            <div class="mt-3 text-end">
              <button type="submit" class="btn btn-primary">Tambah User</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel User -->
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-3">Daftar User</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Saldo</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $user)
                  <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>Rp {{ number_format($user->balance, 0, ',', '.') }}</td>
                    <td>
                      <form action="{{ route('admin.hapus.user', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data user</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
