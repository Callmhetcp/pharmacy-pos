<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;


class DemoExpenseSeeder extends Seeder
{

    public function run(): void
    {

        $user = User::first();

        $categories = ExpenseCategory::all();


        if(!$user || $categories->isEmpty())
        {
            return;
        }



       $start = Expense::count() + 1;


        for($i = $start; $i <= $start + 199; $i++)
        {

            $category = $categories->random();


            Expense::create([


                'expense_number' => 
                    'EXP-' . str_pad($i,6,'0',STR_PAD_LEFT),



                'expense_category_id' => 
                    $category->id,



                'amount' => 
                    random_int(5000,200000),



                'expense_date' => now()->subDays(random_int(0,180)),



                'payment_method' => 
                    collect([
                        'Cash',
                        'Transfer',
                        'Card'
                    ])->random(),



                'description' =>
                    $category->name.' payment',



                'receipt' => null,



                'user_id' =>
                    $user->id,


            ]);

        }

    }

}