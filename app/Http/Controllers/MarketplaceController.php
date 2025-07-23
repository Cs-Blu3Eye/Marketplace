<?php
// app/Http/Controllers/MarketplaceController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Tampilkan daftar semua produk di marketplace.
     */
    public function index()
    {
        $products = Product::where('stock', '>', 0)->orderBy('created_at', 'desc')->paginate(12);
        return view('marketplace.index', compact('products'));
    }

    /**
     * Tampilkan detail produk tertentu.
     */
    public function show(Product $product)
    {
        return view('marketplace.show', compact('product'));
    }
}

?>