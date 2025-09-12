<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 既存のデータを更新（public -> published）
        DB::table('posts')->where('status', 'public')->update(['status' => 'published']);

        // ENUM値を変更
        DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM('published', 'draft') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 既存のデータを更新（published -> public）
        DB::table('posts')->where('status', 'published')->update(['status' => 'public']);

        // ENUM値を元に戻す
        DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM('public', 'private', 'draft') DEFAULT 'draft'");
    }
};
