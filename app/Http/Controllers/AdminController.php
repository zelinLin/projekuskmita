<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin (termasuk daftar user).
     */
    public function index()
    {
        $users = User::all(); // Ambil semua data user dari database
        return view('dashboard.admin', compact('users'));
    }

    /**
     * Menyimpan user baru dari form tambah user.
     */
    public function tambahUser(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,bank_mini,siswa',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menampilkan semua user.
     */
    public function tampilkanUser()
    {
        $users = User::all();
        return view('dashboard.admin', compact('users'));
    }

    /**
     * Menampilkan riwayat transaksi (termasuk relasi user).
     */
    public function riwayatTransaksi()
    {
        $transactions = Transaction::with('user')->latest()->get();
        return view('riwayat-transaksi', compact('transactions'));
    }

    /**
     * Menghapus user berdasarkan ID.
     */
    public function hapusUser($id)
    {
        $user = User::findOrFail($id);

        // Hindari menghapus diri sendiri
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
