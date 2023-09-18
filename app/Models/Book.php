<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Meaning;

class Book extends Model
{
    use HasFactory;

    public function meanings(): HasMany
    {
        return $this->hasMany(Meaning::class);
    }
}
