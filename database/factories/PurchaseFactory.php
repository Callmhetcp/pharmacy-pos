<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [

            'purchase_number' => 'PUR-' . fake()->unique()->numberBetween(100000, 999999),

            'supplier_id' => Supplier::inRandomOrder()->value('id'),

            'invoice_number' => 'INV-' . fake()->numberBetween(10000,99999),

            'purchase_date' => fake()->dateTimeBetween('-6 months', 'now'),

            'user_id' => User::where('role','admin')->value('id')
                        ?? User::inRandomOrder()->value('id'),

        ];
    }
}