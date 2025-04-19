<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menyimpan transaksi baru
     */
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'type'    => 'required|in:top_up,withdraw',
        'amount'  => 'required|numeric|min:1000',
    ]);

    $user = User::findOrFail($request->user_id);

    // Cek apakah yang login adalah bankmini
    if (auth()->user()->role === 'bank_mini') {
        // Langsung update saldo
        if ($request->type === 'top_up') {
            $user->balance += $request->amount;
        } elseif ($request->type === 'withdraw') {
            if ($user->balance < $request->amount) {
                return back()->with('error', 'Saldo tidak mencukupi untuk withdraw.');
            }
            $user->balance -= $request->amount;
        }

        $user->save();

        // Simpan transaksi langsung disetujui
        Transaction::create([
            'user_id' => $request->user_id,
            'type'    => $request->type,
            'amount'  => $request->amount,
            'status'  => 'completed',
        ]);

        return back()->with('success', 'Transaksi berhasil!');
    }

    // Jika bukan bankmini, transaksi dibuat sebagai pending
    Transaction::create([
        'user_id' => $request->user_id,
        'type'    => $request->type,
        'amount'  => $request->amount,
        'status'  => 'pending',
    ]);

    return back()->with('success', 'Transaksi berhasil dikirim dan menunggu persetujuan.');
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
        $transactions = Transaction::with(['user', 'receiver',])
            ->latest()
            ->get();

        return view('riwayat-transaksi', compact('transactions'));
    }

    public function approve($id)
{
    $transaction = Transaction::findOrFail($id);

    if ($transaction->status !== 'pending') {
        return back()->with('error', 'Transaksi sudah diproses.');
    }

    $user = $transaction->user;

    if ($transaction->type === 'top_up') {
        $user->balance += $transaction->amount;
    } elseif ($transaction->type === 'withdraw') {
        if ($user->balance < $transaction->amount) {
            return back()->with('error', 'Saldo tidak mencukupi untuk withdraw.');
        }
        $user->balance -= $transaction->amount;
    }

    $user->save();
    $transaction->status = 'approved';
    $transaction->save();

    return back()->with('success', 'Transaksi disetujui.');
}

public function reject($id)
{
    $transaction = Transaction::findOrFail($id);

    if ($transaction->status !== 'pending') {
        return back()->with('error', 'Transaksi sudah diproses.');
    }

    $transaction->status = 'rejected';
    $transaction->save();

    return back()->with('success', 'Transaksi ditolak.');
}

}
