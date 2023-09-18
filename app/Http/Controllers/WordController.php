<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\Book;

use function Laravel\Prompts\select;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedBookId = $request->get('book_id', 1);

        $words = Word::query()
                    ->whereHas('meanings', function ($query) use ($selectedBookId) {
                        $query->where('book_id', $selectedBookId);
                    })
                    ->with(['meanings' => function ($query) use ($selectedBookId) {
                        $query->where('book_id', $selectedBookId);
                    }])
                    ->orderBy('meanings.index_no')->paginate(20);

        // dd($words);

        $books = Book::select('id', 'name')->get();

        return Inertia::render('Words/Index', [
            'books' => $books,
            'selectedBookId' => $selectedBookId,
            'words' => $words,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWordRequest $request)
    {
        $newWord = new Word;
        $newWord->english = $request->newWordEnglish;
        $newWord->part_of_speech = $request->newWordPartOfSpeech;
        $newWord->save();

        return to_route('meaning.edit');
    }

    /**
     * Display the specified resource.
     */
    public function show(Word $word)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Word $word)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWordRequest $request, Word $word)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        //
    }
}
