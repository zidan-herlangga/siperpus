<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // MENAMBAHKAN kolom 'password' setelah kolom 'email_verified_at'
            $table->string('password')->after('email_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // MENGHAPUS kolom 'password' jika migrasi di-rollback
            $table->dropColumn('password');
        });
    }
};