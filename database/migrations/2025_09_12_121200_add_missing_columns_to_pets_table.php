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
        Schema::table('pets', function (Blueprint $table) {
            $table->string('breed')->nullable()->comment('品種');
            $table->integer('estimated_age')->nullable()->comment('推定年齢');
            $table->text('profile_description')->nullable()->comment('プロフィール説明');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['breed', 'estimated_age', 'profile_description']);
        });
    }
};
