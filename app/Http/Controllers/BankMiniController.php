<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class BankMiniController extends Controller
{
    public function index() {
        $users = User::where('role', 'siswa')->get(); // hanya siswa
        return view('dashboard.bankmini', compact('users'));
    }
    
}
