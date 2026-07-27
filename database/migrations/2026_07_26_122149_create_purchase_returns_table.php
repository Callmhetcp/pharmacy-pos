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
        Schema::create('purchase_returns', function (Blueprint $table) {

            $table->id();

            $table->string('return_number')->unique();

            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();

            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();

            $table->date('return_date');

            $table->string('reason');

            $table->text('notes')->nullable();

            $table->enum('status', [
                'Pending',
                'Completed',
                'Cancelled'
            ])->default('Completed');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
