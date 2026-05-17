<?php

namespace App\Enums;

enum BorrowingStatus: string
{
    case Pending = 'Pending';
    case Dipinjam = 'Dipinjam';
    case Dikembalikan = 'Dikembalikan';
    case Batal = 'Batal';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Dipinjam => 'Dipinjam',
            self::Dikembalikan => 'Dikembalikan',
            self::Batal => 'Batal',
        };
    }
}