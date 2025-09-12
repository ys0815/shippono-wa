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
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->comment('投稿を一意に識別するID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('投稿者のユーザーID');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade')->comment('投稿に関連するペットID');
            $table->enum('type', ['gallery', 'interview'])->comment('投稿の種類');
            $table->string('title')->comment('投稿タイトル');
            $table->text('content')->nullable()->comment('投稿本文');
            $table->enum('status', ['published', 'draft'])->default('draft')->comment('投稿の状態');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
