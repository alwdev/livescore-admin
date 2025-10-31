<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Note: For MySQL, alter ENUM set. For SQLite, ENUM is created as TEXT so we can skip.
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE articles MODIFY COLUMN type ENUM('sports_news','match_analysis','football_tips') NOT NULL DEFAULT 'sports_news'");
        } else {
            // For other drivers (e.g., sqlite, pgsql), attempt a generic approach or skip
            // If it's sqlite, enum behaves like TEXT so no change needed
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Revert back to the original two values
            DB::statement("ALTER TABLE articles MODIFY COLUMN type ENUM('sports_news','match_analysis') NOT NULL DEFAULT 'sports_news'");
        } else {
            // No-op for sqlite / others
        }
    }
};
