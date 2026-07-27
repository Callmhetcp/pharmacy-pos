<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {

            $table->id();


            // Medicine being adjusted
            $table->foreignId('medicine_id')
                ->constrained()
                ->cascadeOnDelete();


            // Increase or decrease
            $table->enum('type', [
                'increase',
                'decrease'
            ]);


            // Adjustment quantity
            $table->integer('quantity');


            // Quantity before adjustment
            $table->integer('old_quantity');


            // Quantity after adjustment
            $table->integer('new_quantity');


            // Reason for adjustment
            $table->string('reason');


            // Optional reference number
            $table->string('reference_number')
                ->unique();


            // User who performed adjustment
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
