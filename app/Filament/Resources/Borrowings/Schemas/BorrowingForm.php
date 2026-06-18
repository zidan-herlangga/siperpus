<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use App\Enums\BorrowingStatus;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Pilih Siswa
                Select::make('student_id')
                    ->label('Nama Siswa')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),

                // Pilih Buku (FIXED: Validasi Stok)
                Select::make('book_id')
                    ->label('Judul Buku')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->required()
                    ->reactive() // Menandakan field ini berinteraksi dengan field lain jika perlu
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Optional: Jika buku diganti, otomatis set status jadi Pending lagi jika di mode Edit
                        if ($get('id')) { 
                             $set('status', BorrowingStatus::Pending->value);
                        }
                    }),

                // Tanggal Pinjam
                DatePicker::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('due_date', Carbon::parse($state)->addDays((int) config('library.borrow_duration_days', 7)));
                        }
                    }),

                // Jatuh Tempo
                DatePicker::make('due_date')
                    ->label('Jatuh Tempo')
                    ->required(),

                // Tanggal Kembali
                DatePicker::make('return_date')
                    ->label('Tanggal Kembali')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Opsional: Jika tanggal kembali diisi, auto hitung denda (jika logic di form mau ditaruh di sini)
                        // Namun lebih aman biarkan logic denda di Model Observer
                    }),

                // Denda (readonly)
                TextInput::make('fine')
                    ->label('Denda')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(), // Pastikan value terkirim walaupun disabled
                

                // Status (FIXED: Dibuat Live & Editable agar workflow berjalan)
                Select::make('status')
                    ->label('Status')
                    ->options(BorrowingStatus::class)
                    // Jangan set default di sini jika form ini dipakai untuk Edit juga
                    // Karena set default akan memaksa record Edit kembali ke Pending setiap kali disimpan
                    // ->default(BorrowingStatus::Pending) 
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Logika Tambahan Form:
                        // Jika Admin ubah status jadi 'Dikembalikan', isi tanggal kembali otomatis jika kosong
                        if ($state === BorrowingStatus::Dikembalikan->value && !$get('return_date')) {
                            $set('return_date', now()->format('Y-m-d'));
                        }
                        
                        // Jika Admin ubah status jadi 'Dipinjam', pastikan tanggal pinjam ada
                        if ($state === BorrowingStatus::Dipinjam->value && !$get('borrow_date')) {
                            $set('borrow_date', now()->format('Y-m-d'));
                        }
                    }),
            ]);
    }
}