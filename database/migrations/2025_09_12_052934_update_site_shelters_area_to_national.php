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
        // 里親サイトのareaをfoster_siteからnationalに更新
        DB::table('shelters')
            ->where('kind', 'site')
            ->where('area', 'foster_site')
            ->update(['area' => 'national']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 里親サイトのareaをnationalからfoster_siteに戻す
        DB::table('shelters')
            ->where('kind', 'site')
            ->where('area', 'national')
            ->update(['area' => 'foster_site']);
    }
};
