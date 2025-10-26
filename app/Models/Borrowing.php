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
        static::updating(function (Borrowing $borrowing) {
            $originalStatus = $borrowing->getOriginal('status');

            // STOCK handling (tetap seperti sebelumnya)
            if ($borrowing->isDirty('status')) {
                if ($originalStatus === 'Dipinjam' && $borrowing->status === 'Dikembalikan') {
                    // Sebelum increment stock, kita finalisasi denda (di bawah)
                    $borrowing->book->increment('stock');
                } elseif ($originalStatus === 'Dikembalikan' && $borrowing->status === 'Dipinjam') {
                    $borrowing->book->decrement('stock');
                }
            }

            // FINALISASI DENDA saat berubah dari Dipinjam -> Dikembalikan
            if (
                $originalStatus === 'Dipinjam'
                && $borrowing->status === 'Dikembalikan'
            ) {
                // Pastikan return_date tercatat; jika belum diisi, set sekarang
                if (empty($borrowing->return_date)) {
                    $borrowing->return_date = now();
                }

                // Hitung dan simpan denda final
                $borrowing->fine = $borrowing->calculateFine();
            }

            // Jika admin membatalkan pengembalian (kembali jadi Dipinjam),
            // kita tidak otomatis menghapus fine — tapi bisa direset jika ingin:
            if (
                $originalStatus === 'Dikembalikan'
                && $borrowing->status === 'Dipinjam'
            ) {
                // opsi: reset fine ke NULL atau 0
                // $borrowing->fine = null;
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
