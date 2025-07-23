<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Kunci asing ke tabel users, jika user terhapus, transaksi juga terhapus
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique(); // ID pesanan unik dari Midtrans
            $table->decimal('total_amount', 10, 2); // Total jumlah transaksi
            $table->string('snap_token')->nullable(); // Token Snap dari Midtrans
            $table->string('status')->default('pending'); // Status transaksi (pending, paid, failed, expire, dll.)
            $table->json('payment_details')->nullable(); // Detail pembayaran dari Midtrans webhook
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
