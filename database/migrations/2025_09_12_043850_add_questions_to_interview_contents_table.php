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
        Schema::table('interview_contents', function (Blueprint $table) {
            $table->text('question1')->nullable()->comment('新しい家族との出会い');
            $table->text('question2')->nullable()->comment('迎える前の不安と準備');
            $table->text('question3')->nullable()->comment('迎えた後の変化と喜び');
            $table->text('question4')->nullable()->comment('未来の里親へのメッセージ');
            $table->text('question5')->nullable()->comment('最後に一言');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_contents', function (Blueprint $table) {
            $table->dropColumn(['question1', 'question2', 'question3', 'question4', 'question5']);
        });
    }
};
