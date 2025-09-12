<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shelters', function (Blueprint $table) {
            $table->string('area')->default('national')->after('name')->comment('所在地カテゴリ');
            $table->enum('kind', ['facility', 'site', 'unknown'])->default('facility')->after('area')->comment('施設/サイト/不明');
        });

        // 外部キーを外してから nullable に変更
        Schema::table('shelters', function (Blueprint $table) {
            try {
                $table->dropForeign(['prefecture_id']);
            } catch (\Throwable $e) {
                // 既に外れている/キー不存在の場合は無視
            }
        });

        // MySQL用にカラムをNULL許容へ変更
        DB::statement('ALTER TABLE shelters MODIFY prefecture_id BIGINT UNSIGNED NULL');

        // 外部キーを再付与（NULL許容のまま）
        Schema::table('shelters', function (Blueprint $table) {
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // 外部キーを外す
        Schema::table('shelters', function (Blueprint $table) {
            try {
                $table->dropForeign(['prefecture_id']);
            } catch (\Throwable $e) {
            }
        });

        // prefecture_id を NOT NULL に戻す（デフォルトは 1 に仮設定。必要に応じて修正）
        DB::statement('ALTER TABLE shelters MODIFY prefecture_id BIGINT UNSIGNED NOT NULL');

        Schema::table('shelters', function (Blueprint $table) {
            $table->dropColumn(['area', 'kind']);
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->cascadeOnDelete();
        });
    }
};
