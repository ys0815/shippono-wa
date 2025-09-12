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
        Schema::create('pets', function (Blueprint $table) {
            $table->id()->comment('ペットを一意に識別するID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('飼い主のユーザーID');
            $table->string('name')->comment('ペットの名前');
            $table->enum('species', ['dog', 'cat', 'rabbit', 'other'])->comment('種類');
            $table->date('birth_date')->nullable()->comment('誕生日');
            $table->enum('gender', ['male', 'female', 'unknown'])->comment('性別');
            $table->string('profile_image_url')->comment('プロフィールアイコン画像URL');
            $table->string('header_image_url')->comment('プロフィール背景画像URL');
            $table->foreignId('shelter_id')->constrained('shelters')->comment('お迎え元保護施設ID');
            $table->date('rescue_date')->comment('お迎え日');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
