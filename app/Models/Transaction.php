<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'recipient_id',
        'description',
    ];
    
    /**
     * Relasi ke User (pengirim)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke User (penerima, jika ada)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
