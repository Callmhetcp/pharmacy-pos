<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_return_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sales_return_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity');

            $table->decimal('selling_price', 10, 2);

            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_return_items');
    }
};