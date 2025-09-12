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
            if (Schema::hasColumn('interview_contents', 'q1_answer')) {
                $table->dropColumn('q1_answer');
            }
            if (Schema::hasColumn('interview_contents', 'q2_answer')) {
                $table->dropColumn('q2_answer');
            }
            if (Schema::hasColumn('interview_contents', 'q3_answer')) {
                $table->dropColumn('q3_answer');
            }
            if (Schema::hasColumn('interview_contents', 'q4_answer')) {
                $table->dropColumn('q4_answer');
            }
            if (Schema::hasColumn('interview_contents', 'q5_answer')) {
                $table->dropColumn('q5_answer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ロールバック時は何もしない（カラムを復元しない）
    }
};
