<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->index('status');
            $table->index('borrow_date');
            $table->index('due_date');
            $table->index('return_date');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('condition');
            $table->index('stock');
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('class');
        });

        Schema::table('book_comments', function (Blueprint $table) {
            $table->index('book_id');
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['borrow_date']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['return_date']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['condition']);
            $table->dropIndex(['stock']);
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['class']);
        });

        Schema::table('book_comments', function (Blueprint $table) {
            $table->dropIndex(['book_id']);
        });
    }
};
