<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
     use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'total_amount',
        'snap_token',
        'status',
        'payment_details',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_details' => 'array', // Cast JSON kolom ke array
    ];

    /**
     * Definisikan relasi many-to-one dengan User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
