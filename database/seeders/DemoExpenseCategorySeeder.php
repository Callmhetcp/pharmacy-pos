<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class DemoExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Electricity',
                'description' => 'Power bills and electricity expenses',
                'status' => 'Active',
            ],

            [
                'name' => 'Staff Salary',
                'description' => 'Monthly staff payments',
                'status' => 'Active',
            ],

            [
                'name' => 'Transport',
                'description' => 'Delivery and transportation costs',
                'status' => 'Active',
            ],

            [
                'name' => 'Rent',
                'description' => 'Shop rent and premises expenses',
                'status' => 'Active',
            ],

            [
                'name' => 'Maintenance',
                'description' => 'Equipment repairs and maintenance',
                'status' => 'Active',
            ],

            [
                'name' => 'Internet',
                'description' => 'Internet and communication bills',
                'status' => 'Active',
            ],

            [
                'name' => 'Generator Fuel',
                'description' => 'Fuel purchase for power backup',
                'status' => 'Active',
            ],

        ];

        foreach ($categories as $category) {

            ExpenseCategory::updateOrCreate(

                [
                    'name' => $category['name'],
                ],

                [
                    'description' => $category['description'],
                    'status' => $category['status'],
                ]

            );

        }
    }
}