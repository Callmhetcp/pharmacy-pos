<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSaleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

           $customers = Customer::all();
            $cashier = User::where('role', 'cashier')->first();
            $medicines = Medicine::where('quantity', '>', 0)->get();

            logger('Customers: ' . $customers->count());
            logger('Cashier: ' . ($cashier ? 'FOUND' : 'NOT FOUND'));
            logger('Medicines: ' . $medicines->count());

            if ($customers->isEmpty() || !$cashier || $medicines->isEmpty()) {
                logger('DemoSaleSeeder exited early.');
                return;
            }

            $paymentMethods = [
                'Cash',
                'POS',
                'Transfer',
            ];

            // Number of demo sales
            for ($i = 1; $i <= 100; $i++) {

                $subtotal = 0;

                $sale = Sale::create([

                    'receipt_number' => 'REC-' . str_pad($i, 6, '0', STR_PAD_LEFT),

                    'customer_id' => $customers->random()->id,

                    'user_id' => $cashier->id,

                    'cashier_id' => $cashier->id,

                    'sale_date' => now()->subDays(random_int(1, 180)),

                    'subtotal' => 0,

                    'vat_percent' => 7.5,

                    'vat_amount' => 0,

                    'total_amount' => 0,

                    'amount_paid' => 0,

                    'balance' => 0,

                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],

                ]);

                $items = $medicines->random(random_int(1, 5));

                foreach ($items as $medicine) {

                    if ($medicine->quantity <= 0) {
                        continue;
                    }

                    $quantity = random_int(
                        1,
                        min($medicine->quantity, 10)
                    );

                    $itemTotal = $quantity * $medicine->selling_price;

                    $subtotal += $itemTotal;

                    SaleItem::create([

                        'sale_id' => $sale->id,

                        'medicine_id' => $medicine->id,

                        'quantity' => $quantity,

                        'unit_price' => $medicine->selling_price,

                        'cost_price' => $medicine->cost_price,

                        'subtotal' => $itemTotal,

                    ]);

                    $medicine->decrement('quantity', $quantity);
                }

                $vatAmount = round($subtotal * 0.075, 2);

                $totalAmount = $subtotal + $vatAmount;

                $sale->update([

                    'subtotal' => $subtotal,

                    'vat_amount' => $vatAmount,

                    'total_amount' => $totalAmount,

                    'amount_paid' => $totalAmount,

                    'balance' => 0,

                ]);
            }
        });
    }
}