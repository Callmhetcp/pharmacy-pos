<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE sale_drafts
            MODIFY COLUMN status
            ENUM('open','held','completed','cancelled')
            NOT NULL DEFAULT 'open'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE sale_drafts
            MODIFY COLUMN status
            ENUM('open','completed','cancelled')
            NOT NULL DEFAULT 'open'
        ");
    }
};