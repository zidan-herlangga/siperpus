<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $indexes = [
            "ALTER TABLE `visitors` ADD INDEX IF NOT EXISTS `visitors_ip_address_created_at_index` (`ip_address`, `created_at`)",
            "ALTER TABLE `borrowings` ADD INDEX IF NOT EXISTS `borrowings_status_index` (`status`)",
            "ALTER TABLE `borrowings` ADD INDEX IF NOT EXISTS `borrowings_student_id_status_index` (`student_id`, `status`)",
            "ALTER TABLE `borrowings` ADD INDEX IF NOT EXISTS `borrowings_due_date_status_index` (`due_date`, `status`)",
            "ALTER TABLE `books` ADD INDEX IF NOT EXISTS `books_author_index` (`author`)",
            "ALTER TABLE `books` ADD INDEX IF NOT EXISTS `books_category_id_index` (`category_id`)",
            "ALTER TABLE `students` ADD INDEX IF NOT EXISTS `students_is_active_index` (`is_active`)",
            "ALTER TABLE `book_comments` ADD INDEX IF NOT EXISTS `book_comments_is_approved_index` (`is_approved`)",
            "ALTER TABLE `book_comments` ADD INDEX IF NOT EXISTS `book_comments_book_id_is_approved_index` (`book_id`, `is_approved`)",
            "ALTER TABLE `testimonials` ADD INDEX IF NOT EXISTS `testimonials_is_approved_index` (`is_approved`)",
        ];

        foreach ($indexes as $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {
                // MySQL < 8.0 or MariaDB doesn't support IF NOT EXISTS for indexes
                // Fallback: try without IF NOT EXISTS, ignore duplicate key errors
                try {
                    $fallbackSql = preg_replace('/ADD INDEX IF NOT EXISTS/', 'ADD INDEX', $sql, 1);
                    DB::statement($fallbackSql);
                } catch (\Exception $inner) {
                    // Index already exists — skip silently
                }
            }
        }
    }

    public function down(): void
    {
        $drops = [
            ['table' => 'visitors',     'index' => 'visitors_ip_address_created_at_index'],
            ['table' => 'borrowings',   'index' => 'borrowings_status_index'],
            ['table' => 'borrowings',   'index' => 'borrowings_student_id_status_index'],
            ['table' => 'borrowings',   'index' => 'borrowings_due_date_status_index'],
            ['table' => 'books',        'index' => 'books_author_index'],
            ['table' => 'books',        'index' => 'books_category_id_index'],
            ['table' => 'students',     'index' => 'students_is_active_index'],
            ['table' => 'book_comments','index' => 'book_comments_is_approved_index'],
            ['table' => 'book_comments','index' => 'book_comments_book_id_is_approved_index'],
            ['table' => 'testimonials', 'index' => 'testimonials_is_approved_index'],
        ];

        foreach ($drops as $drop) {
            try {
                DB::statement("ALTER TABLE `{$drop['table']}` DROP INDEX `{$drop['index']}`");
            } catch (\Exception $e) {
                // Index doesn't exist — skip
            }
        }
    }
};
