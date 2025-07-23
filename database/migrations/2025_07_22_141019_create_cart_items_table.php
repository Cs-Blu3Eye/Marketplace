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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            // Kunci asing ke tabel carts, jika cart terhapus, item juga terhapus
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            // Kunci asing ke tabel products, jika produk terhapus, item juga terhapus
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Jumlah produk dalam item keranjang
            $table->decimal('price', 10, 2); // Harga produk saat ditambahkan ke keranjang (untuk historis)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
