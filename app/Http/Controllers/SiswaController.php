<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    /**
     * Menampilkan dashboard siswa dengan data transaksi terbaru.
     */
    public function dashboard()
    {
        $siswa = Auth::user();

        $transactions = Transaction::with('receiver')
            ->where('user_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.siswa', compact('siswa', 'transactions'));
    }

    /**
     * Proses top-up saldo siswa atau oleh bank mini.
     */
    public function topUp(Request $request)
{
    $request->validate([
        'jumlah' => 'required|numeric|min:1000',
    ]);

    $user = $request->filled('user_id') 
        ? User::findOrFail($request->user_id) 
        : Auth::user();

    // tunggu persetujuan admin/bank mini
    Transaction::create([
        'user_id'    => $user->id,
        'amount'     => $request->jumlah,
        'type'       => 'top_up',
        'status'     => 'pending',
        'description'=> 'Top-up oleh ' . Auth::user()->name,
    ]);

    return redirect()->back()->with('success', 'Permintaan top-up dikirim, menunggu persetujuan.');
}
    /**
     * Proses penarikan saldo siswa atau oleh bank mini.
     */
    public function tarikSaldo(Request $request)
{
    $request->validate([
        'jumlah' => 'required|numeric|min:1000',
        'type'   => 'required|in:top_up,withdraw',
    ]);

    $user = $request->filled('user_id') 
        ? User::findOrFail($request->user_id) 
        : Auth::user();

    // Tidak langsung mengurangi saldo
    if ($user->balance < $request->jumlah) {
        return redirect()->back()->with('error', 'Saldo tidak cukup!');
    }

    Transaction::create([
        'user_id'    => $user->id,
        'amount'     => $request->jumlah,
        'type'       => 'withdraw',
        'status'     => 'pending',
        'description'=> 'Tarik saldo oleh ' . Auth::user()->name,
    ]);

    return redirect()->back()->with('success', 'Permintaan penarikan dikirim, menunggu persetujuan.');
}


    /**
     * Proses transfer saldo antar siswa.
     */
    public function transferSaldo(Request $request)
    {
        $request->validate([
            'target_siswa' => 'required|string',
            'jumlah'       => 'required|numeric|min:1000',
        ]);

        $pengirim   = Auth::user();
        $targetUser = User::where('name', $request->target_siswa)->first();

        if (!$targetUser) {
            return back()->withErrors(['target_siswa' => 'Siswa tidak ditemukan.']);
        }

        if ($pengirim->balance < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Saldo tidak mencukupi.']);
        }

        DB::beginTransaction();

        try {
            $pengirim->update([
                'balance' => $pengirim->balance - $request->jumlah
            ]);

            $targetUser->update([
                'balance' => $targetUser->balance + $request->jumlah
            ]);

            Transaction::create([
                'user_id'     => $pengirim->id,
                'recipient_id'=> $targetUser->id,
                'type'        => 'transfer',
                'amount'      => $request->jumlah,
                'status'      => 'completed',
                'description' => "Transfer saldo ke {$targetUser->name}",
            ]);

            DB::commit();

            return back()->with('success', 'Transfer berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan, silakan coba lagi.']);
        }
    }

    /**
     * Cetak riwayat transaksi dalam format PDF.
     * Siswa hanya bisa cetak milik sendiri, admin/bank_mini bisa cetak semua.
     */
    public function cetakRiwayat()
{
    $user = Auth::user();

    if ($user->role === 'siswa') {
        $transactions = Transaction::with('receiver')
            ->where('user_id', $user->id)
            ->whereDate('created_at', now())
            ->latest()
            ->get();

        $pdf = PDF::loadView('pdf.riwayat-transaksi-siswa', [
            'siswa'        => $user,
            'transactions'=> $transactions
        ]);

        return $pdf->download('riwayat_transaksi_' . $user->name . '_hari_ini.pdf');
    }

    // Admin / bankmini
    $transactions = Transaction::with(['receiver', 'user'])
        ->whereDate('created_at', now())
        ->latest()
        ->get();

    $pdf = PDF::loadView('pdf.riwayat-transaksi-semua', [
        'transactions' => $transactions
    ]);

    return $pdf->download('riwayat_transaksi_semua_user_hari_ini.pdf');
}
}
