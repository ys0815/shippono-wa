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
        Schema::create('shelters', function (Blueprint $table) {
            $table->id()->comment('保護団体を一意に識別するID');
            $table->string('name')->comment('団体名');
            $table->foreignId('prefecture_id')->constrained('prefectures')->comment('都道府県ID');
            $table->string('website_url')->comment('公式サイトのURL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelters');
    }
};
