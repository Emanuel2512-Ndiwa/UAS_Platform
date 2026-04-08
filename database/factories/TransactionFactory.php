<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'order_type' => fake()->randomElement(['online_pickup', 'online_delivery', 'offline_walkin']),
            'user_id' => User::factory(),
            'service_id' => Service::factory(),
            'total_amount' => fake()->numberBetween(10000, 50000),
            'payment_status' => 'unpaid',
            'service_status' => 'menunggu',
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
