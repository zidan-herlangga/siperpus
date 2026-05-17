<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('students')->whereNull('avatar')->update(['avatar' => '']);
        DB::statement('ALTER TABLE students MODIFY COLUMN avatar VARCHAR(255) NOT NULL DEFAULT \'\'');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE students MODIFY COLUMN avatar VARCHAR(255) NULL DEFAULT NULL');
    }
};
