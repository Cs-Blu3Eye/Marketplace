<?php
// app/Services/MidtransService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        // dd(config('services.midtrans'));

        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Buat transaksi Snap.
     *
     * @param array $params Parameter transaksi
     * @return string Snap Token
     */
    public function createSnapTransaction(array $params)
    {
        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            // Tangani error, log atau lempar exception
            Log::error('Midtrans Snap Error: ' . $e->getMessage(), ['params' => $params]);
            throw new \Exception('Gagal membuat transaksi Midtrans. Silakan coba lagi.');
        }
    }

    /**
     * Proses notifikasi dari Midtrans.
     *
     * @return \Midtrans\Notification
     */
    public function getNotification()
    {
        return new Notification();
    }
}
