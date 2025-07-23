<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang belanja.
     */
    public function index()
    {
        $cartItems = [];
        $total = 0;

        // Jika user login, ambil dari database
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItems = $cart->cartItems()->with('product')->get();
                $total = $cart->total;
            }
        } else {
            // Jika tidak login, ambil dari sesi
            $sessionCart = session()->get('cart', []);
            foreach ($sessionCart as $productId => $item) {
                $product = Product::find($productId);
                if ($product) {
                    $cartItems[] = (object) [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ];
                    $total += $item['quantity'] * $item['price'];
                }
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Tambahkan produk ke keranjang.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $quantity = $request->quantity;

        // Jika user login, simpan ke database
        if (Auth::check()) {
            $user = Auth::user();
            $cart = $user->cart()->firstOrCreate([]); // Buat keranjang jika belum ada

            $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

            if ($cartItem) {
                // Update jumlah jika produk sudah ada di keranjang
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Tambahkan produk baru ke keranjang
                $cart->cartItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price, // Simpan harga saat ini
                ]);
            }
        } else {
            // Jika tidak login, simpan ke sesi
            $cart = session()->get('cart', []);

            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->price,
                    "image" => $product->image,
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Perbarui jumlah produk di keranjang.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:' . $product->stock,
        ]);

        $quantity = $request->quantity;

        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
                if ($cartItem) {
                    if ($quantity > 0) {
                        $cartItem->quantity = $quantity;
                        $cartItem->save();
                    } else {
                        $cartItem->delete(); // Hapus item jika kuantitas 0
                    }
                }
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                if ($quantity > 0) {
                    $cart[$product->id]['quantity'] = $quantity;
                } else {
                    unset($cart[$product->id]); // Hapus item jika kuantitas 0
                }
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function remove(Product $product)
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
                if ($cartItem) {
                    $cartItem->delete();
                }
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                unset($cart[$product->id]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    /**
     * Sinkronisasi keranjang dari sesi ke database saat user login.
     */
    public function syncCartAfterLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = $user->cart()->firstOrCreate([]);
            $sessionCart = session()->get('cart', []);

            foreach ($sessionCart as $productId => $itemData) {
                $product = Product::find($productId);
                if ($product) {
                    $cartItem = $cart->cartItems()->where('product_id', $productId)->first();
                    if ($cartItem) {
                        // Tambahkan kuantitas dari sesi ke keranjang database
                        $cartItem->quantity += $itemData['quantity'];
                        $cartItem->save();
                    } else {
                        // Tambahkan item dari sesi ke keranjang database
                        $cart->cartItems()->create([
                            'product_id' => $productId,
                            'quantity' => $itemData['quantity'],
                            'price' => $itemData['price'],
                        ]);
                    }
                }
            }
            session()->forget('cart'); // Hapus keranjang dari sesi setelah disinkronkan
        }
    }
}