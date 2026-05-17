<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->timestamp('due_soon_sent_at')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->date('return_date')->nullable();
            $table->decimal('fine', 10, 2)->default(0)->nullable();
            $table->enum('status', ['Pending', 'Dipinjam', 'Dikembalikan', 'Batal'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};