<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoMedicineSeeder extends Seeder
{
    public function run(): void
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

        for ($i = 1; $i <= 100; $i++) {

            $item = $catalog[array_rand($catalog)];

            $category = Category::where('name', $item[1])->first();

            Medicine::create([

                'name' => $item[0],

                'barcode' => 'GP' . str_pad((string) mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT),

                'quantity' => rand(20, 800),

                'minimum_stock' => rand(10, 30),

                'cost_price' => $item[2],

                'selling_price' => $item[3],

                'expiry_date' => Carbon::now()->addDays(rand(180, 1095)),

                'category_id' => $category?->id,

            ]);
        }
    }
}