<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('Pending', 'Dipinjam', 'Dikembalikan', 'Batal', 'Dipesan') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('Pending', 'Dipinjam', 'Dikembalikan', 'Batal') NOT NULL DEFAULT 'Pending'");
    }
};
