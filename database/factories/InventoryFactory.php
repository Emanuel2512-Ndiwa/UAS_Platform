<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'item_name' => fake()->word(),
            'stock' => fake()->numberBetween(0, 50),
            'unit' => fake()->randomElement(['Liter', 'Kg']),
            'status' => 'safe',
        ];
    }
}
