<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_returns', function (Blueprint $table) {

            $table->id();

            $table->string('return_number')->unique();

            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('return_date');

            $table->string('reason');

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->enum('status', [
                'Pending',
                'Completed',
                'Cancelled'
            ])->default('Completed');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};