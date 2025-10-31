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
            if ($borrowing->isDirty('status')) {
                $originalStatus = $borrowing->getOriginal('status');

                if ($originalStatus === 'Dipinjam' && $borrowing->status === 'Dikembalikan') {
                    $borrowing->book->increment('stock');
                } elseif ($originalStatus === 'Dikembalikan' && $borrowing->status === 'Dipinjam') {
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

    // Hitung denda berjalan
    public function calculateFine(): int
    {
        $now = \Carbon\Carbon::now();

        // Jika buku sudah dikembalikan
        if ($this->return_date) {
            if ($this->return_date->isAfter($this->due_date)) {
                // Hitung selisih hari keterlambatan
                $daysLate = $this->due_date->diffInDays($this->return_date);
                return $daysLate * 1000;
            }
            return 0;
        }

        // Jika belum dikembalikan tapi sudah lewat due_date
        if ($now->isAfter($this->due_date)) {
            $daysLate = max(1, $this->due_date->diffInDays($now));
            return $daysLate * 1000;
        }

        return 0;
    }

    // Accessor agar bisa dipanggil sebagai $borrowing->fine_amount
    public function getFineAmountAttribute(): int
    {
        return $this->calculateFine();
    }
}
