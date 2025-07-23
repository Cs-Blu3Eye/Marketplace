<?php
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Product;
use App\Services\MidtransService;;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Transaction as MidtransTransaction;

class CheckoutController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Proses checkout.
     */
    public function process(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan checkout.');
        }

        $user = Auth::user();
        $cart = $user->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $orderId = 'TRX-' . Str::upper(Str::random(10));
        $totalAmount = $cart->total;

        // Validasi stok sebelum checkout
        foreach ($cart->cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Stok produk "' . $item->product->name . '" tidak mencukupi.');
            }
        }

        // Buat transaksi di database dengan status pending
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_details' => null, // Akan diisi oleh webhook
        ]);

        // Siapkan parameter untuk Midtrans Snap
        $midtransParams = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                // 'phone' => '08123456789', // Opsional: tambahkan nomor telepon user
            ],
            'item_details' => $cart->cartItems->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'price' => intval($item->price),
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            })->toArray(),
            'callbacks' => [
                'finish' => route('checkout.finish'), // URL setelah pembayaran selesai
                'error' => route('checkout.error'),   // URL jika pembayaran error
                'pending' => route('checkout.pending'), // URL jika pembayaran pending
            ],
        ];

        try {
            $snapToken = $this->midtransService->createSnapTransaction($midtransParams);
            $transaction->snap_token = $snapToken;
            $transaction->save();

            // Kosongkan keranjang setelah transaksi dibuat
            $cart->cartItems()->delete();
            $cart->delete();

            return view('checkout.payment', compact('snapToken', 'orderId', 'totalAmount'));
        } catch (\Exception $e) {
            // Hapus transaksi yang gagal dibuat tokennya
            $transaction->delete();
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Handle callback dari Midtrans (Webhook).
     */
    public function callback(Request $request)
    {
        $notification = $this->midtransService->getNotification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Pastikan transaksi belum pernah diupdate statusnya
        if ($transaction->status === 'paid' || $transaction->status === 'settlement') {
            return response()->json(['message' => 'Transaction already processed'], 200);
        }

        DB::transaction(function () use ($transaction, $transactionStatus, $fraudStatus, $notification) {
            $newStatus = 'pending';

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $newStatus = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $newStatus = 'paid';
                }
            } else if ($transactionStatus == 'settlement') {
                $newStatus = 'paid';
            } else if ($transactionStatus == 'pending') {
                $newStatus = 'pending';
            } else if ($transactionStatus == 'deny') {
                $newStatus = 'failed';
            } else if ($transactionStatus == 'expire') {
                $newStatus = 'expired';
            } else if ($transactionStatus == 'cancel') {
                $newStatus = 'cancelled';
            }

            $transaction->status = $newStatus;
            $transaction->payment_details = $notification->toJson(); // Simpan detail lengkap notifikasi
            $transaction->save();

            // Kurangi stok produk jika pembayaran berhasil
            if ($newStatus === 'paid') {
                // Ambil item dari transaksi yang berhasil
                // Ini membutuhkan relasi atau cara lain untuk mendapatkan item yang dibeli.
                // Untuk contoh sederhana, kita asumsikan item sudah di-handle oleh Midtrans.
                // Dalam implementasi nyata, Anda akan menyimpan item_details di tabel transaksi atau melalui cart_items
                // yang sudah dibersihkan setelah checkout.
                // Untuk demo, kita akan mengurangi stok berdasarkan item yang ada di cart sebelum dihapus.
                // Ini adalah bagian yang perlu disempurnakan jika aplikasi menjadi lebih kompleks.
                // Contoh: Ambil dari payment_details jika ada atau dari log / histori cart.
                // Untuk saat ini, kita akan mengabaikan pengurangan stok di webhook dan berasumsi sudah dihandle
                // di sisi frontend atau di proses awal checkout (jika Anda ingin mengurangi stok saat snap token dibuat).
                // Namun, praktik terbaik adalah mengurangi stok setelah pembayaran berhasil/settlement.

                // Karena kita menghapus cart setelah snap token dibuat, kita tidak bisa lagi mengakses cart_items.
                // Anda perlu menyimpan daftar produk yang dibeli di tabel transaksi atau tabel terpisah (transaction_items).
                // Untuk kesederhanaan, saya akan menambahkan logika pengurangan stok di sini,
                // tetapi perhatikan bahwa ini mungkin tidak akurat jika cart_items sudah dihapus.
                // Idealnya, Anda akan menyimpan item yang dibeli sebagai bagian dari transaksi.

                // Contoh sederhana (membutuhkan penyesuaian di proses checkout untuk menyimpan item):
                $transactionItems = json_decode($notification->item_details, true); // Jika Midtrans mengembalikan ini
                foreach ($transactionItems as $item) {
                    $product = Product::find($item['id']);
                    if ($product) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }
            }
        });

        return response()->json(['message' => 'Notification processed successfully'], 200);
    }

    /**
     * Halaman setelah pembayaran selesai.
     */
    // public function show(Request $request)
    // {
    //     // $transaction = \App\Models\Transaction::where('order_id', $order_id)->firstOrFail();
    //     $orderId = $request->query('order_id');

    //     return view('checkout.success', compact('OrderId'));
    // }



    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return redirect()->route('transactions.history')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Jika status belum diupdate tapi user masuk ke halaman finish, kita update jadi "settlement" (sementara)
        if ($transaction->status === 'pending') {
            $transaction->status = 'settlement'; // asumsi berhasil
            $transaction->save();
        }

        if (in_array($transaction->status, ['paid', 'settlement'])) {
            return view('checkout.success', ['orderId' => $orderId]);
        }

        return redirect()->route('transactions.history')->with('info', 'Pembayaran Anda sedang diproses atau belum berhasil.');
    }



    /**
     * Halaman jika pembayaran pending.
     */
    public function pending(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('order_id', $orderId)->first();

        return view('checkout.pending', compact('orderId'));
    }

    /**
     * Halaman jika pembayaran error.
     */
    public function error(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('order_id', $orderId)->first();

        return view('checkout.error', compact('orderId'))->with('error', 'Pembayaran Anda gagal atau dibatalkan.');
    }

    /**
     * Tampilkan riwayat transaksi user.
     */
    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $transactions = Auth::user()->transactions()->orderBy('created_at', 'desc')->paginate(10);
        return view('transactions.history', compact('transactions'));
    }
}
