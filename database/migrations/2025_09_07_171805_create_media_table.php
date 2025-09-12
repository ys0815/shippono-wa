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
        Schema::create('media', function (Blueprint $table) {
            $table->id()->comment('メディアを一意に識別するID');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->comment('関連する投稿ID');
            $table->string('url')->comment('ファイルの保存先URL');
            $table->enum('type', ['image', 'video'])->comment('メディアの種類');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
