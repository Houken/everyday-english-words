<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'name' => '英単語ターゲット1900',
                'publisher' => '旺文社',
                'version' => 6,
                'published_at' => 2020,
            ],
            [
                'name' => '英単語ターゲット1400',
                'publisher' => '旺文社',
                'version' => 5,
                'published_at' => 2020,
            ],
            [
                'name' => 'ターゲット中学英単語1800',
                'publisher' => '旺文社',
                'version' => 4,
                'published_at' => 2019,
            ],
            [
                'name' => 'システム英単語',
                'publisher' => '駿台文庫',
                'version' => 5,
                'published_at' => 2019,
            ],
        ]);
    }
}
