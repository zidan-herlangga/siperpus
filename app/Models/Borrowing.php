<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Notifications\NewBorrowingNotification;
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

    // --- LOGIKA OTOMATIS UNTUK STOK BUKU + FINALISASI DENDA ---
    protected static function booted(): void
    {
        static::created(function (Borrowing $borrowing) {
            $admins = \App\Models\Admin::all();

            Notification::make()
                ->title('Peminjaman Buku Baru!')
                ->body($borrowing->student->name . ' meminjam buku "' . $borrowing->book->title . '"')
                ->sendToDatabase($admins);
        });
        
        static::updating(function (Borrowing $borrowing) {
            $originalStatus = $borrowing->getOriginal('status');
            $newStatus = $borrowing->status;

            // 1. Pending → Dipinjam → Kurangi stok
            if ($originalStatus === 'Pending' && $newStatus === 'Dipinjam') {
                $borrowing->book->decrement('stock');
            }

            // 2. Dipinjam → Dikembalikan → Tambah stok + hitung denda
            if ($originalStatus === 'Dipinjam' && $newStatus === 'Dikembalikan') {
                $borrowing->book->increment('stock');

                if (empty($borrowing->return_date)) {
                    $borrowing->return_date = now();
                }

                $borrowing->fine = $borrowing->calculateFine();
            }

            // 2b. Pending → Dikembalikan → Kembalikan stok
            if ($originalStatus === 'Pending' && $newStatus === 'Dikembalikan') {
                // Jika langsung dari Pending ke Dikembalikan, kembalikan stok
                $borrowing->book->increment('stock');

                if (empty($borrowing->return_date)) {
                    $borrowing->return_date = now();
                }
            }

            // 3. Dikembalikan → Dipinjam → Kurangi stok lagi
            if ($originalStatus === 'Dikembalikan' && $newStatus === 'Dipinjam') {
                $borrowing->book->decrement('stock');
            }

            // 4. Pending → Batal → Kembalikan stok
            if ($originalStatus === 'Pending' && $newStatus === 'Batal') {
                $borrowing->book->increment('stock');
            }
        });
    }
    // --- AKHIR LOGIKA OTOMATIS ---

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

    // Hitung denda berjalan (1 hari = 1000)
    public function calculateFine(): int
    {
        $now = Carbon::now();

        // Jika buku sudah dikembalikan -> hitung berdasar return_date
        if ($this->return_date) {
            if ($this->return_date->isAfter($this->due_date)) {
                $daysLate = $this->due_date->diffInDays($this->return_date);
                return $daysLate * 1000;
            }
            return 0;
        }

        // Jika belum dikembalikan tapi sudah lewat due_date -> hitung per hari sampai sekarang
        if ($now->isAfter($this->due_date)) {
            $daysLate = max(1, $this->due_date->diffInDays($now));
            return $daysLate * 1000;
        }

        return 0;
    }

    // Accessor agar bisa dipanggil sebagai $borrowing->fine_amount
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
