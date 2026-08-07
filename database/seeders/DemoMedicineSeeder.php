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
        $category = Category::first();

        Medicine::create([
            'name' => 'Paracetamol Test',
            'barcode' => 'GP1234567890',
            'quantity' => 100,
            'minimum_stock' => 10,
            'cost_price' => 200,
            'selling_price' => 300,
            'expiry_date' => Carbon::now()->addYear(),
            'category_id' => $category->id,
        ]);
    }
}