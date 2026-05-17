<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Migrasi data kategori dari kolom category di tabel books
        $categories = DB::table('books')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tambah kolom category_id di books
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        });

        // Isi category_id berdasarkan relasi
        DB::statement("UPDATE books SET category_id = (SELECT id FROM categories WHERE categories.name = books.category)");

        // Hapus kolom category lama (opsional)
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('category', 255)->nullable();
        });

        DB::statement("UPDATE books SET category = (SELECT name FROM categories WHERE categories.id = books.category_id)");

        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
