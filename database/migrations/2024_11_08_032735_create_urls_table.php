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
        Schema::create('urls', function (Blueprint $table) {
           $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('link_name')->nullable()->comment('ชื่อลิงก์');
            $table->text('original_url')->comment('ลิงก์ต้นฉบับ');
            $table->string('short_url')->comment('ลิงก์ที่เปลี่ยนแปลง');
            $table->string('short_path_name')->nullable()->comment('ชื่อพาร์ธลิงก์สั้น');
            $table->boolean('is_expire')->default(0)->comment('มีวันหมดอายุหรือไม่');
            $table->date('expire_date')->nullable()->comment('วันหมดอายุ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
