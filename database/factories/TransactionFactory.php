<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_id' => strtoupper('ORD-' . $this->faker->unique()->bothify('###??##')),
            'total_amount' => $this->faker->randomFloat(2, 10000, 500000),
            'snap_token' => Str::random(32),
            'status' => $this->faker->randomElement(['pending', 'paid', 'failed', 'expire']),
            'payment_details' => json_encode([
                'bank' => 'bca',
                'va_number' => $this->faker->numerify('123456####'),
                'payment_type' => 'bank_transfer',
                'transaction_time' => now()->toDateTimeString(),
            ]),
        ];
    }
}
