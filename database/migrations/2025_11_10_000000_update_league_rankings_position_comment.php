<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('league_rankings', function (Blueprint $table) {
            $table->unsignedTinyInteger('position')->comment('1-12')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('league_rankings', function (Blueprint $table) {
            $table->unsignedTinyInteger('position')->comment('1-10')->change();
        });
    }
};
