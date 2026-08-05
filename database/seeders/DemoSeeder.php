<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            DemoUserSeeder::class,

            DemoCategorySeeder::class,

            DemoSupplierSeeder::class,

            DemoMedicineSeeder::class,

            DemoCustomerSeeder::class,
            // We'll add these later
            DemoExpenseCategorySeeder::class,

            DemoExpenseSeeder::class,
            
            DemoPurchaseSeeder::class,

            DemoSaleSeeder::class,

            DemoPurchaseReturnSeeder::class,

            DemoSalesReturnSeeder::class,



        ]);
    }
}