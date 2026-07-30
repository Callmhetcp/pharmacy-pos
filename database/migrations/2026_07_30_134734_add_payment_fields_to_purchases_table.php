<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {

            $table->decimal('amount_paid',12,2)
                  ->default(0)
                  ->after('grand_total');

            $table->decimal('balance',12,2)
                  ->default(0)
                  ->after('amount_paid');

            $table->enum('payment_status',[
                'Unpaid',
                'Partial',
                'Paid'
            ])->default('Unpaid')
              ->after('balance');

        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {

            $table->dropColumn([
                'amount_paid',
                'balance',
                'payment_status'
            ]);

        });
    }
};