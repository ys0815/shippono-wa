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
            $table->id()->comment('インタビューID');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->comment('関連する投稿ID');
            $table->text('q1_answer')->comment('新しい家族との出会いの回答');
            $table->text('q2_answer')->comment('迎える前の不安と準備の回答');
            $table->text('q3_answer')->comment('迎えた後の変化と喜びの回答');
            $table->text('q4_answer')->comment('未来の里親へのメッセージの回答');
            $table->text('q5_answer')->comment('最後に一言の回答');
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
