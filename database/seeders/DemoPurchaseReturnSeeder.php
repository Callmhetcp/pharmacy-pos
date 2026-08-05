<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPurchaseReturnSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::where('role', 'admin')->first();

            $purchases = Purchase::with('items')
                ->whereHas('items')
                ->get();


            if (!$user || $purchases->isEmpty()) {
                return;
            }


            for ($i = 1; $i <= 50; $i++) {


                $purchase = $purchases->random();


                $return = PurchaseReturn::create([

                    'return_number' => 'PR-' . str_pad($i, 6, '0', STR_PAD_LEFT),

                    'purchase_id' => $purchase->id,

                    'supplier_id' => $purchase->supplier_id,

                    'return_date' => now()
                        ->subDays(random_int(1,90)),

                    'reason' => fake()->randomElement([
                        'Damaged stock',
                        'Expired medicine',
                        'Wrong supply',
                        'Supplier issue'
                    ]),

                    'total_amount' => 0,

                    'status' => 'Completed',

                    'user_id' => $user->id,

                ]);



                $total = 0;


                $items = $purchase->items
                    ->random(min(2,$purchase->items->count()));



                foreach ($items as $item) {


                    $quantity = random_int(
                        1,
                        min($item->quantity,5)
                    );


                    $subtotal =
                        $quantity * $item->cost_price;



                    PurchaseReturnItem::create([

                        'purchase_return_id' => $return->id,

                        'medicine_id' => $item->medicine_id,

                        'quantity' => $quantity,

                        'cost_price' => $item->cost_price,

                        'subtotal' => $subtotal,

                    ]);



                    // Remove returned stock

                    $item->medicine
                        ->decrement(
                            'quantity',
                            $quantity
                        );


                    $total += $subtotal;

                }



                $return->update([

                    'total_amount' => $total

                ]);

            }

        });
    }
}