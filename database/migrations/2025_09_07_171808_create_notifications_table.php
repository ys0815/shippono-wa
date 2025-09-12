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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id()->comment('通知ID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('通知を受け取るユーザーID');
            $table->enum('type', ['like', 'follow', 'comment'])->comment('通知の種類');
            $table->foreignId('source_user_id')->constrained('users')->onDelete('cascade')->comment('通知の発生元ユーザーID');
            $table->foreignId('related_pet_id')->nullable()->constrained('pets')->onDelete('cascade')->comment('関連するペットID');
            $table->boolean('is_read')->default(false)->comment('既読フラグ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
