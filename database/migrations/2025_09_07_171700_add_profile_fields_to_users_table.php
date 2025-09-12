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
        Schema::table('users', function (Blueprint $table) {
            // nameをdisplay_nameに変更するため、新しいカラムを追加
            $table->string('display_name')->after('email')->comment('公開されるハンドルネーム');

            // SNSアカウント情報
            $table->string('sns_x')->nullable()->after('display_name')->comment('X（旧Twitter）アカウントURL');
            $table->string('sns_instagram')->nullable()->after('sns_x')->comment('InstagramアカウントURL');
            $table->string('sns_facebook')->nullable()->after('sns_instagram')->comment('FacebookアカウントURL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'display_name',
                'sns_x',
                'sns_instagram',
                'sns_facebook'
            ]);
        });
    }
};
