<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    /**
     * Definisikan relasi many-to-many dengan CartItem.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
