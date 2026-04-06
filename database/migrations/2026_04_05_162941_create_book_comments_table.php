<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_approved')->default(true); // Set true jika tidak perlu moderasi ketat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_comments');
    }
};