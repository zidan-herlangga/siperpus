<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Notifiable;

class Borrowing extends Model
{
    use Notifiable;
    use HasFactory;

    protected $fillable = [
        'student_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'fine',
        'status',
        'due_soon_sent_at',
        'last_reminder_sent_at',
    ];

    // Gunakan $casts sesuai konvensi Laravel
    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'due_soon_sent_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];

    // --- NOTIFIKASI PEMINJAMAN BARU ---
    protected static function booted(): void
    {
        static::created(function (Borrowing $borrowing) {
            $admins = \App\Models\Admin::all();

            Notification::make()
                ->title('Peminjaman Buku Baru!')
                ->body(($borrowing->student?->name ?? 'Unknown') . ' meminjam buku "' . ($borrowing->book?->title ?? 'Unknown') . '"')
                ->sendToDatabase($admins);
        });
    }
    // --- AKHIR NOTIFIKASI ---

    public function scopeNeedReminder(Builder $query): void
    {
        $query->whereNull('return_date')
            ->whereIn('status', ['Dipinjam'])
            ->where(function (Builder $q) {
                $q->whereNull('last_reminder_sent_at')
                  ->orWhereDate('last_reminder_sent_at', '<', now()->subDay());
            });
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

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
        $now = Carbon::now();
        $finePerDay = (int) config('library.fine_per_day', 1000);

        // Jika buku sudah dikembalikan -> hitung berdasar return_date
        if ($this->return_date) {
            if ($this->return_date->isAfter($this->due_date)) {
                $daysLate = $this->due_date->diffInDays($this->return_date);
                return $daysLate * $finePerDay;
            }
            return 0;
        }

        // Jika belum dikembalikan tapi sudah lewat due_date -> hitung per hari sampai sekarang
        if ($now->isAfter($this->due_date)) {
            $daysLate = max(1, $this->due_date->diffInDays($now));
            return $daysLate * $finePerDay;
        }

        return 0;
    }

    // Accessor agar bisa dipanggil sebagai $borrowing->fine_amount
    public function isOverdue(): bool
    {
        return $this->status === 'Dipinjam' && $this->due_date && now()->isAfter($this->due_date);
    }

    public function isDueSoon(): bool
    {
        return $this->status === 'Dipinjam' && !$this->isOverdue()
            && $this->due_date && now()->diffInDays($this->due_date) <= 3;
    }

    public function getFineAmountAttribute(): int
    {
        // Jika sudah dikembalikan -> gunakan nilai final di DB
        if ($this->status === 'Dikembalikan') {
            return (int) $this->fine;
        }

        // Jika masih Dipinjam -> hitung dinamis
        return $this->calculateFine();
    }
}