<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menyimpan transaksi baru (top up atau withdraw).
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type'    => 'required|in:top_up,withdraw',
            'amount'  => 'required|numeric|min:1000',
        ]);

        Transaction::create([
            'user_id' => $request->user_id,
            'type'    => $request->type,
            'amount'  => $request->amount,
        ]);

        return back()->with('success', 'Transaksi berhasil!');
    }

    /**
     * Menampilkan semua transaksi (untuk admin dashboard).
     */
    public function index()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->get();

        return view('dashboard.admin', compact('transactions'));
    }

    /**
     * Menampilkan riwayat transaksi lengkap (admin/bank mini).
     */
    public function riwayatTransaksi()
    {
        $transactions = Transaction::with(['user', 'receiver'])
            ->latest()
            ->get();

        return view('riwayat-transaksi', compact('transactions'));
    }
}
