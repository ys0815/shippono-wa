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
            $table->id()->comment('家族リンクID');
            $table->string('link_group_id')->comment('リンクグループID（UUID）');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('動物の飼い主ユーザーID');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade')->comment('ペットID');
            $table->timestamps();

            // 同じペットは一つの家族グループにのみ所属
            $table->unique('pet_id');
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
