<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        // Use raw SQL since Laravel Blueprint can't cleanly alter MySQL ENUM without doctrine
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft','pending','active','completed','rejected') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        // Revert: convert any 'rejected' rows back to 'draft' before removing the enum value
        DB::statement("UPDATE events SET status='draft' WHERE status='rejected'");
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft','pending','active','completed') NOT NULL DEFAULT 'draft'");
    }
};
