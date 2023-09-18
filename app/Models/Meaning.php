<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Word;
use App\Models\Book;

class Meaning extends Model
{
    use HasFactory;

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
