<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover_image',
        'slug',
        'author',
        'publisher',
        'year',
        'isbn',
        'category',
        'synopsis',
        'shelf_code',
        'stock',
    ];

    /**
     * Optimasi Eloquent default.
     */
    protected $casts = [
        'year' => 'integer',
        'stock' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function ($book) {
            $book->slug = Str::slug($book->title);
        });
    }



    /**
     * Kolom yang sering diambil bisa diset default select
     * supaya Eloquent tidak ambil semua field.
     */
    protected $visible = [
        'id',
        'title',
        'cover_image',
        'slug',
        'author',
        'publisher',
        'year',
        'isbn',
        'category',
        'synopsis',
        'shelf_code',
        'stock',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relasi peminjaman buku
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Scope pencarian cepat
     */
    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('author', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%");
            });
        }
    }

    /**
     * Scope filter kategori
     */
    public function scopeCategory($query, $category)
    {
        if (!empty($category)) {
            $query->where('category', $category);
        }
    }
}
