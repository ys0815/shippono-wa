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
        Schema::create('likes', function (Blueprint $table) {
            $table->id()->comment('いいねID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('いいねを押したユーザーID');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade')->comment('いいねされたペットID');
            $table->timestamps();

            // 同じユーザーが同じペットに複数回いいねできないようにする
            $table->unique(['user_id', 'pet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
