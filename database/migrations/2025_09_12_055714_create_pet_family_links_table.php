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
        Schema::create('pet_family_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet1_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('pet2_id')->constrained('pets')->onDelete('cascade');
            $table->timestamps();

            // 同じペット同士の重複リンクを防ぐ
            $table->unique(['pet1_id', 'pet2_id']);
            // 逆方向の重複も防ぐ（pet1_id=1, pet2_id=2 と pet1_id=2, pet2_id=1 は同じ）
            $table->unique(['pet2_id', 'pet1_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_family_links');
    }
};
