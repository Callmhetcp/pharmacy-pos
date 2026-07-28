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
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            $table->string('pharmacy_name')->default('HealthPlus Pharmacy');

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->string('currency')->default('₦');

            $table->decimal('tax',5,2)->default(0);

            $table->string('logo')->nullable();

            $table->text('receipt_footer')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};