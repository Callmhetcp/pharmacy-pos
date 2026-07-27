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
        Schema::table('purchase_return_items', function (Blueprint $table) {

            $table->dropConstrainedForeignId('purchase_item_id');

            $table->decimal('cost_price', 10, 2)
                  ->after('quantity');

            $table->decimal('subtotal', 12, 2)
                  ->after('cost_price');

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_return_items', function (Blueprint $table) {

            $table->dropColumn([
                'cost_price',
                'subtotal'
            ]);

            $table->foreignId('purchase_item_id')
                  ->after('purchase_return_id')
                  ->constrained()
                  ->cascadeOnDelete();

        });
    }
};
