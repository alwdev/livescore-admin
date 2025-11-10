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
        // ลบ foreign key เดิม (ถ้ามี)
        Schema::table('league_rankings', function (Blueprint $table) {
            // ถ้า constraint เดิมชื่ออื่น ให้เปลี่ยนชื่อใน dropForeign ด้วย
            $table->dropForeign(['league_id']);
        });

        // เพิ่ม foreign key ใหม่ให้ชี้ไป competitions
        Schema::table('league_rankings', function (Blueprint $table) {
            $table->foreign('league_id')->references('id')->on('competitions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ foreign key ที่ชี้ competitions
        Schema::table('league_rankings', function (Blueprint $table) {
            $table->dropForeign(['league_id']);
        });

        // เพิ่มกลับไปที่ leagues (ถ้าต้องการ rollback กลับไปที่เดิม)
        Schema::table('league_rankings', function (Blueprint $table) {
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
        });
    }
};
