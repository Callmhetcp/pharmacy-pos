<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::where('role', 'admin')->first()
                ?? User::first();

            $suppliers = Supplier::all();
            $medicines = Medicine::all();

            if ($suppliers->isEmpty() || $medicines->isEmpty() || !$user) {
                return;
            }


            for ($i = 1; $i <= 250; $i++) {


                // Create Purchase
                $purchase = Purchase::create([

                    'purchase_number' => 'PUR-' . str_pad($i, 6, '0', STR_PAD_LEFT),

                    'supplier_id' => $suppliers->random()->id,

                    'invoice_number' => 'INV-' . random_int(10000, 99999),

                    'purchase_date' => now()
                        ->subDays(random_int(1, 180))
                        ->format('Y-m-d'),

                    'user_id' => $user->id,

                    'grand_total' => 0,

                    'amount_paid' => 0,

                    'balance' => 0,

                    'payment_status' => 'Unpaid',

                ]);


                $grandTotal = 0;


                // Select medicines for this purchase
                $items = $medicines->random(random_int(2, 8));


                foreach ($items as $medicine) {


                    $quantity = random_int(10, 100);


                    $subtotal = $quantity * $medicine->cost_price;


                    $grandTotal += $subtotal;



                    PurchaseItem::create([

                        'purchase_id' => $purchase->id,

                        'medicine_id' => $medicine->id,

                        'batch_number' => 'BATCH-' . strtoupper(fake()->bothify('??###')),

                        'expiry_date' => now()
                            ->addMonths(random_int(12, 36))
                            ->format('Y-m-d'),

                        'quantity' => $quantity,

                        'cost_price' => $medicine->cost_price,

                        'selling_price' => $medicine->selling_price,

                        'subtotal' => $subtotal,

                    ]);


                    // Increase medicine stock

                    $medicine->increment('quantity', $quantity);

                }



                // Generate payment status

                $paymentStatus = fake()->randomElement([

                    'Paid',

                    'Paid',

                    'Partial',

                    'Unpaid',

                ]);



                if ($paymentStatus === 'Paid') {

                    $amountPaid = $grandTotal;

                }

                elseif ($paymentStatus === 'Partial') {

                    $amountPaid = round(
                        $grandTotal * fake()->randomFloat(2, 0.3, 0.8),
                        2
                    );

                }

                else {

                    $amountPaid = 0;

                }



                $balance = $grandTotal - $amountPaid;



                // Update purchase totals

                $purchase->update([

                    'grand_total' => $grandTotal,

                    'amount_paid' => $amountPaid,

                    'balance' => $balance,

                    'payment_status' => $paymentStatus,

                ]);

            }

        });
    }
}