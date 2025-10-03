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

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Menghitung denda berdasarkan tanggal kembali dan jatuh tempo.
     *
     * @return int
     */
    public function calculateFine(): int
    {
        if ($this->return_date && $this->return_date->isAfter($this->due_date)) {
            $lateDays = $this->due_date->diffInDays($this->return_date);
            return $lateDays * 5000; // Denda Rp5.000 per hari
        }
        return 0;
    }
}