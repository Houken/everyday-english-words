<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meaning;
use App\Models\Word;
use Inertia\Inertia;
use App\Services\WordEditService;

use function PHPUnit\Framework\isNull;

class MeaningController extends Controller
{
    public function index(Request $request)
    {
        $selectedBookId = (int)$request->get('book_id', 1);

        $meanings = Meaning::where('book_id', $selectedBookId)
            ->with('word')
            ->orderBy('index_no')
            ->paginate(20);

        // dd($selectedBookId, $meanings);

        return Inertia::render('Meanings/Index', [
            'selectedBookId' => $selectedBookId,
            'meanings' => $meanings,
        ]);
    }

    public function edit(Request $request, WordEditService $wordEditService)
    {
        // inputボックスで指定された、単語帳とインデックス番号を受け取る
        $attributes = $request->only(['book_id', 'selectedIdx']);
        // dd($request, $attributes);
        if ($attributes == []) {
            $attributes = [
                'book_id' => 4,
                'selectedIdx' => 1100,
            ];
        }


            $selectedBookId = !$attributes['book_id'] ? $attributes['book_id'] : 4;
            $selectedIdx = is_numeric($attributes['selectedIdx']) ? $attributes['selectedIdx'] : 800;

        // 指定された単語帳で、指定されたインデックス番号の単語7個分のデータを受け取る
        $selectedMeanings = $wordEditService->selectedMeanings($selectedBookId, $selectedIdx);

        // $search = $selectedMeanings->get(1)->english; // selectedMeaning.englishそのもの？

        $masters = $wordEditService->masters($selectedMeanings);

        // dd($masters);

        return Inertia::render('Meanings/Edit', [
            'masters' => $masters,
            'selectedBookId' => $selectedBookId,
            'selectedIdx' => $selectedIdx,
            'selectedMeanings' => $selectedMeanings,
        ]);
    }

    public function update(Request $request, Meaning $meaning, WordEditService $wordEditService)
    {
        $attributes = $request->only(['id', 'index_no', 'book_id', 'word_id']);

        // $id = (int)$request->get('id');
        // $selectedIdx = (int)$request->get('index_no');
        // $selectedBookId = (int)$request->get('book_id', 4);
        $meaning = Meaning::find($attributes['id']);
        $meaning->word_id = (int)$attributes['word_id'];
        // $meaning->english = $attributes['english'];
        $meaning->save();

        $selectedIdx = (int)$attributes['index_no'] + 1;
        $selectedBookId = (int)$attributes['book_id'];
        $selectedMeanings = $wordEditService->selectedMeanings($selectedBookId, $selectedIdx);

        // $search = $selectedMeanings->get(1)->english; // selectedMeaning.englishそのもの。

        $masters = $wordEditService->masters($selectedMeanings);

        return Inertia::render('Meanings/Edit', [
            'selectedIdx' => $selectedIdx,
            'selectedMeanings' => $selectedMeanings,
            'masters' => $masters,
            'selectedBookId' => $selectedBookId,
        ]);
    }

    public function test()
    {
        return Inertia::render('Meanings/Test');
    }
}
