<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'fine',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    // --- LOGIKA OTOMATIS UNTUK STOK BUKU ---
    protected static function booted(): void
    {
        static::updating(function (Borrowing $borrowing) {
            // Cek apakah kolom 'status' adalah yang sedang diubah
            if ($borrowing->isDirty('status')) {
                $originalStatus = $borrowing->getOriginal('status');

                // Jika buku dikembalikan (status berubah dari Dipinjam -> Dikembalikan)
                if ($originalStatus == 'Dipinjam' && $borrowing->status == 'Dikembalikan') {
                    $borrowing->book->increment('stock');
                }
                // Jika status pengembalian dibatalkan (Dikembalikan -> Dipinjam)
                elseif ($originalStatus == 'Dikembalikan' && $borrowing->status == 'Dipinjam') {
                    $borrowing->book->decrement('stock');
                }
            }
        });
    }
    // --- AKHIR LOGIKA OTOMATIS ---

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function calculateFine(): int
    {
        if ($this->return_date && $this->return_date->isAfter($this->due_date)) {
            $lateDays = $this->due_date->diffInDays($this->return_date);
            return $lateDays * 1000;
        }
        return 0;
    }
}