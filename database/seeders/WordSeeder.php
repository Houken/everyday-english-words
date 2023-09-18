<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Word;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("マスタテーブルの初期データを読み込みます…");

        $wordSplFileObj = new \SplFileObject(__DIR__ . '/data/master-words.csv');
        $wordSplFileObj->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );
        $wordSplFileObj->setCsvControl(",", "\"", "\\");

        $count = 0;
        foreach ($wordSplFileObj as $key => $col) {
            if($key === 0) {
                continue;
            }

            Word::create([
                'id' => trim($col[0]),
                'english' => trim($col[1]),
                'part_of_speech' => trim($col[2]),
            ]);
            $count++;

        }

        $this->command->info("マスタテーブルの単語を{$count}件作成しました。");
    }
}
