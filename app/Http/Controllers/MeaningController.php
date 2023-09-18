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
        $attributes = $request->only(['book_id', 'searchText', 'selectedIdx']);
        if ($attributes == []) {
            $attributes = [
                'book_id' => 4,
                'searchText' => '',
                'selectedIdx' => 800,
            ];
        }

            $selectedBookId = !$attributes['book_id'] ? $attributes['book_id'] : 4;
            $selectedIdx = is_numeric($attributes['selectedIdx']) ? $attributes['selectedIdx'] : 800;

        // 指定された単語帳で、指定されたインデックス番号の単語7個分のデータを受け取る
        $selectedMeanings = $wordEditService->selectedMeanings($selectedBookId, $selectedIdx);

        $search = $selectedMeanings->get(1)->english;

        $masters = $wordEditService->masters($selectedMeanings);

        // dd($masters);

        return Inertia::render('Meanings/Edit', [
            'masters' => $masters,
            'search' => $search,
            'selectedBookId' => $selectedBookId,
            'selectedIdx' => $selectedIdx,
            'selectedMeanings' => $selectedMeanings,
        ]);
    }

    public function update(Request $request, Meaning $meaning, WordEditService $wordEditService)
    {
        $id = (int)$request->get('id');
        $selectedIdx = (int)$request->get('index_no');
        $selectedBookId = (int)$request->get('book_id', 4);
        $meaning = Meaning::find($id);
        $meaning->word_id = (int)$request->get('word_id');
        $meaning->english = $request->get('english');

        $meaning->save();

        $selectedMeanings = $wordEditService->selectedMeanings($selectedBookId, $selectedIdx);

        $search = $selectedMeanings->get(1)->english;

        $masters = $wordEditService->masters($selectedMeanings);

        return Inertia::render('Meanings/Edit', [
            'selectedIdx' => $selectedIdx + 1,
            'search' => $search,
            'selectedMeanings' => $selectedMeanings,
            'masters' => $masters,
            'selectedBookId' => $selectedBookId,
        ]);
    }
}