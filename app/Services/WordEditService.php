<?php

namespace App\Services;

use App\Models\Meaning;
use App\Models\Word;

class WordEditService
{
    public function selectedMeanings($selectedBookId, $selectedIdx)
    {
        $previousIdx = $selectedIdx - 1;
        $nextIndex = $selectedIdx + 5;

        $selectedMeanings = Meaning::where('book_id', $selectedBookId)
            ->where('index_no', '>=', $previousIdx)
            ->where('index_no', '<=', $nextIndex)
            ->with('word')
            ->get();

        return $selectedMeanings;
    }

    public function masters($selectedMeanings)
    {
        $search = $selectedMeanings->get(1)->english;

        $masters = Word::where('english', 'like', $search . '%')
            ->select('id', 'english', 'part_of_speech')
            ->get();

        return $masters;
    }
}
