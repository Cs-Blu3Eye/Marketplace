<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price', // Harga produk saat ditambahkan ke keranjang
    ];

    /**
     * Definisikan relasi many-to-one dengan Cart.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Definisikan relasi many-to-one dengan Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
