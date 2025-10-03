<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year',
        'isbn',
        'category',
        'shelf_code',
        'stock',
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }
}