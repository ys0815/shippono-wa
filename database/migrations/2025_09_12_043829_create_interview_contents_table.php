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
        Schema::create('interview_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->text('question1')->comment('新しい家族との出会い');
            $table->text('question2')->comment('迎える前の不安と準備');
            $table->text('question3')->comment('迎えた後の変化と喜び');
            $table->text('question4')->comment('未来の里親へのメッセージ');
            $table->text('question5')->comment('最後に一言');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_contents');
    }
};
