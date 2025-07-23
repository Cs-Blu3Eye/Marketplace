<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Definisikan relasi one-to-one dengan User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Definisikan relasi one-to-many dengan CartItem.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Hitung total harga di keranjang.
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
