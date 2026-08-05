<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSalesReturnSeeder extends Seeder
{
    public function run(): void
    {

        DB::transaction(function () {


            $sales = Sale::with('items')
                ->whereHas('items')
                ->get();


            if ($sales->isEmpty()) {
                return;
            }



            for ($i = 1; $i <= 50; $i++) {


                $sale = $sales->random();



                $return = SalesReturn::create([

                    'return_number' =>
                    'SR-' . str_pad($i,6,'0',STR_PAD_LEFT),

                    'sale_id' => $sale->id,

                    'customer_id' => $sale->customer_id,

                    'return_date' =>
                    now()->subDays(random_int(1,90)),

                    'reason' => fake()->randomElement([

                        'Customer complaint',

                        'Wrong medicine',

                        'Damaged package',

                        'Customer changed mind'

                    ]),

                    'total_amount'=>0,

                    'status'=>'Completed',

                ]);



                $total = 0;



                $items = $sale->items
                    ->random(min(1,$sale->items->count()));



                foreach($items as $item){


                    $quantity = random_int(
                        1,
                        min($item->quantity,3)
                    );


                    $subtotal =
                    $quantity * $item->unit_price;



                    SalesReturnItem::create([

                        'sales_return_id'=>$return->id,

                        'medicine_id'=>$item->medicine_id,

                        'quantity'=>$quantity,

                        'selling_price'=>$item->unit_price,

                        'subtotal'=>$subtotal,

                    ]);



                    // Return stock back

                    $item->medicine
                        ->increment(
                            'quantity',
                            $quantity
                        );


                    $total += $subtotal;

                }



                $return->update([

                    'total_amount'=>$total

                ]);


            }


        });

    }
}