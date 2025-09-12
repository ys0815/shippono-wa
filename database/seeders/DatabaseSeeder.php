<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // マスタデータ
        $this->call([
            PrefectureSeeder::class,
            ShelterSeeder::class,
        ]);

        // テストユーザー
        User::factory()->create([
            'name' => 'Test User',
            'display_name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
    }
}
