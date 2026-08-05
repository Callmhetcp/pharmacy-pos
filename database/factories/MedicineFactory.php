<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MedicineFactory extends Factory
{
    public function definition(): array
    {
        $catalog = [

            ['Paracetamol 500mg Tablet', 'Analgesics', 250, 400],
            ['Ibuprofen 400mg Tablet', 'Analgesics', 350, 550],
            ['Diclofenac 50mg Tablet', 'Analgesics', 450, 700],
            ['Amoxicillin 500mg Capsule', 'Antibiotics', 850, 1200],
            ['Augmentin 625mg Tablet', 'Antibiotics', 2500, 3200],
            ['Azithromycin 500mg Tablet', 'Antibiotics', 1500, 2000],
            ['Coartem Tablet', 'Antimalarials', 1200, 1700],
            ['Vitamin C 1000mg', 'Vitamins & Supplements', 300, 500],
            ['Feroglobin Capsules', 'Vitamins & Supplements', 1800, 2400],
            ['ORS Sachet', 'Gastrointestinal', 120, 250],

        ];

        $medicine = fake()->randomElement($catalog);

        $category = Category::where('name', $medicine[1])->first();

        return [

            'name' => $medicine[0],

            'barcode' => 'GP' . fake()->unique()->numerify('##########'),

            'quantity' => 0,

            'minimum_stock' => fake()->numberBetween(10, 30),

            'cost_price' => $medicine[2],

            'selling_price' => $medicine[3],

            'expiry_date' => fake()->dateTimeBetween('+6 months', '+3 years'),

            'category_id' => $category?->id,

        ];
    }
}