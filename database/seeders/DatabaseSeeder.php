<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory()->count(20)->create();
        Cart::factory()->has(CartItem::factory()->count(3))->count(5)->create();
        Transaction::factory()->count(10)->create();

        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Fahmi Nur Fadillah',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password
            'role' => 'admin',
        ]);
    }
}
