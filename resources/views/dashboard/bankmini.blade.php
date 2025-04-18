<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Bank Mini</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <div class="max-w-6xl mx-auto p-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center bg-white p-4 rounded shadow mb-6">
      <div>
        <h2 class="text-2xl font-semibold text-gray-800">Bank Mini</h2>
        <p class="text-sm text-gray-500">Dashboard Bank Mini</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('riwayat.transaksi') }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded hover:bg-purple-200 text-sm">Riwayat Transaksi</a>
        <form action="{{ url('/logout') }}" method="POST">
          @csrf
          <button type="submit" class="bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200 text-sm">Logout</button>
        </form>
      </div>
    </div>

    <!-- Notifikasi -->
    @if (session('success'))
      <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <!-- Form Tambah User -->
    <div class="bg-white p-6 rounded shadow mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">Tambah User</h3>
      <form action="{{ route('admin.tambah.user') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-600">Nama</label>
            <input type="text" name="name" class="w-full border px-4 py-2 rounded" required />
          </div>
          <div>
            <label class="block text-gray-600">Email</label>
            <input type="email" name="email" class="w-full border px-4 py-2 rounded" required />
          </div>
          <div>
            <label class="block text-gray-600">Password</label>
            <input type="password" name="password" class="w-full border px-4 py-2 rounded" required />
          </div>
          <div>
            <label class="block text-gray-600">Role</label>
            <select name="role" class="w-full border px-4 py-2 rounded">
              <option value="siswa">Siswa</option>
            </select>
          </div>
        </div>
        <div class="mt-4 text-right">
          <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">Tambah User</button>
        </div>
      </form>
    </div>

    <!-- Form Top-Up & Tarik Saldo -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      
      <!-- Top-Up -->
      <div class="bg-white rounded shadow-sm p-6">
        <h5 class="text-lg font-semibold mb-4 text-gray-800">Top-Up Saldo</h5>
        <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
          @csrf
          <input type="hidden" name="type" value="top_up" />
          <div>
            <label for="user_id_topup" class="block text-sm font-medium text-gray-700 mb-1">User ID (Siswa)</label>
            <input type="number" name="user_id" id="user_id_topup" required
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent" />
          </div>
          <div>
            <label for="amount_topup" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
            <input type="number" name="amount" id="amount_topup" min="1000" required
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent" />
          </div>
          <div>
            <button type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">
              Top-Up
            </button>
          </div>
        </form>
      </div>
      
      <!-- Tarik Saldo -->
      <div class="bg-white rounded shadow-sm p-6">
        <h5 class="text-lg font-semibold mb-4 text-gray-800">Tarik Saldo</h5>
        <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
          @csrf
          <input type="hidden" name="type" value="withdraw" />
          <div>
            <label for="user_id_tarik" class="block text-sm font-medium text-gray-700 mb-1">User ID (Siswa)</label>
            <input type="number" name="user_id" id="user_id_tarik" required
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent" />
          </div>
          <div>
            <label for="amount_tarik" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
            <input type="number" name="amount" id="amount_tarik" min="1000" required
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent" />
          </div>
          <div>
            <button type="submit"
              class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition duration-200">
              Tarik Tunai
            </button>
          </div>
        </form>
      </div>
      
    </div>

    <!-- Daftar Transaksi Pending -->
<div class="bg-white p-6 rounded shadow mb-6">
  <h3 class="text-lg font-semibold mb-4 text-gray-700">Transaksi Menunggu Persetujuan</h3>
  @php
    $pendingTransactions = \App\Models\Transaction::with('user')
      ->where('status', 'pending')
      ->latest()
      ->get();
  @endphp

  @if ($pendingTransactions->isEmpty())
    <p class="text-gray-500 text-sm">Tidak ada transaksi pending.</p>
  @else
    <table class="w-full table-auto divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Nama</th>
          <th class="px-4 py-2">Jenis</th>
          <th class="px-4 py-2">Jumlah</th>
          <th class="px-4 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($pendingTransactions as $trx)
          <tr>
            <td class="px-4 py-2">{{ $trx->id }}</td>
            <td class="px-4 py-2">{{ $trx->user->name }}</td>
            <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $trx->type) }}</td>
            <td class="px-4 py-2">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
            <td class="px-4 py-2 flex gap-2">
              <form action="{{ route('transaksi.approve', $trx->id) }}" method="POST">
                @csrf
                <button class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Setujui</button>
              </form>
              <form action="{{ route('transaksi.reject', $trx->id) }}" method="POST">
                @csrf
                <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Tolak</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

    <!-- Daftar Siswa -->
    <div class="bg-white p-6 rounded shadow mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">Daftar Siswa</h3>
      <div class="w-full overflow-x-auto">
        <table class="w-full table-auto divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($users as $user)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($user->balance, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data siswa</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    
  </div>

</body>
</html>
