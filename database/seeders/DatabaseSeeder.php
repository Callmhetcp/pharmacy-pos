<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        AdminUserSeeder::class,

        DemoUserSeeder::class,

        DemoCategorySeeder::class,
        DemoSupplierSeeder::class,
        DemoMedicineSeeder::class,
        DemoCustomerSeeder::class,

        DemoPurchaseSeeder::class,
        DemoSaleSeeder::class,

        DemoPurchaseReturnSeeder::class,
        DemoSalesReturnSeeder::class,

        DemoExpenseCategorySeeder::class,
        DemoExpenseSeeder::class,
    ]);
}
}

