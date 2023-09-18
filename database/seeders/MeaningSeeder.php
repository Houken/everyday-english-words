<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meaning;

class MeaningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("和訳の初期データを読み込みます…");

        $wordSplFileObj = new \SplFileObject(__DIR__ . '/data/meanings.csv');
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

            Meaning::create([
                'id' => trim($col[0]),
                'book_id' => trim($col[1]),
                'word_id' => trim($col[2]),
                'index_no' => trim($col[3]),
                'english' => trim($col[4]),
                'japanese' => trim($col[5]),
            ]);
            $count++;

        }

        $this->command->info("和訳を{$count}件作成しました。");
    }
}
