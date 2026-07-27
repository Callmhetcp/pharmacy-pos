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
        Schema::table('purchase_returns', function (Blueprint $table) {

            $table->dropColumn('notes');

            $table->decimal('total_amount', 12, 2)
                  ->default(0)
                  ->after('reason');

        });
    }

    /**
     * Reverse the migrations.
     */
      public function down(): void
    {
        Schema::table('purchase_returns', function (Blueprint $table) {

            $table->dropColumn('total_amount');

            $table->text('notes')
                  ->nullable()
                  ->after('reason');

        });
    }
};
