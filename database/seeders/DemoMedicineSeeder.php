<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class DemoMedicineSeeder extends Seeder
{
    public function run(): void
    {
        Medicine::factory()->count(100)->create();
    }
}