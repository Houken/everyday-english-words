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

    public function test(Request $request, WordEditService $wordEditService)
    {
        $attributes = $request->only(['testBookId', 'testTypeIsRead', 'testHintLevel', 'testRangeFrom', 'testRangeTo', 'testQuestions']);
        $bookId = array_key_exists('testBookId', $attributes) ? (int)$attributes['testBookId'] : 3;
        $typeIsRead = array_key_exists('testTypeIsRead', $attributes) ? (boolean)$attributes['testTypeIsRead'] : true;
        $hintLevel = array_key_exists('testHintLevel', $attributes) ? (int)$attributes['testHintLevel'] : 2;
        $rangeFrom = array_key_exists('testRangeFrom', $attributes) ? (int)$attributes['testRangeFrom'] : 1;
        $rangeTo = array_key_exists('testRangeTo', $attributes) ? (int)$attributes['testRangeTo'] : 100;
        $questions = array_key_exists('testQuestions', $attributes) ? (int)$attributes['testQuestions'] : 25;

        $tests = Meaning::where('book_id', $bookId)
                    ->whereBetween('index_no', [$rangeFrom,$rangeTo])
                    ->inRandomOrder()
                    ->take($questions)
                    ->with('word')
                    ->get();
        $splitTests = array_chunk($tests->toArray(), 25);

        $maxIndex = Meaning::where('book_id', $bookId)->count();

        $indexesTo = $wordEditService->generateTo($maxIndex);
        $indexesFrom = $wordEditService->generateFrom($maxIndex);




        return Inertia::render('Meanings/Test', [
            'bookId' => $bookId,
            'typeIsRead' => $typeIsRead,
            'hintLevel' => $hintLevel,
            'rangeFrom' => $rangeFrom,
            'rangeTo' => $rangeTo,
            'maxIndex'=> $maxIndex,
            'indexesTo' => $indexesTo,
            'indexesFrom' => $indexesFrom,
            'questions' => $questions,
            'tests' => $tests,
            'splitTests' => $splitTests,
        ]);
    }
}
