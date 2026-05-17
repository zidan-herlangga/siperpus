<?php

namespace Tests\Unit;

use App\Models\Borrowing;
use Carbon\Carbon;
use Tests\TestCase;

class BorrowingModelTest extends TestCase
{
    public function test_is_overdue_returns_true_when_past_due(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dipinjam',
            'due_date' => Carbon::now()->subDays(5),
        ]);

        $this->assertTrue($borrowing->isOverdue());
    }

    public function test_is_overdue_returns_false_when_not_past_due(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dipinjam',
            'due_date' => Carbon::now()->addDays(5),
        ]);

        $this->assertFalse($borrowing->isOverdue());
    }

    public function test_is_overdue_returns_false_when_returned(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dikembalikan',
            'due_date' => Carbon::now()->subDays(5),
        ]);

        $this->assertFalse($borrowing->isOverdue());
    }

    public function test_is_due_soon_returns_true_within_3_days(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dipinjam',
            'due_date' => Carbon::now()->addDays(2),
        ]);

        $this->assertTrue($borrowing->isDueSoon());
    }

    public function test_is_due_soon_returns_false_beyond_3_days(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dipinjam',
            'due_date' => Carbon::now()->addDays(5),
        ]);

        $this->assertFalse($borrowing->isDueSoon());
    }

    public function test_fine_calculation_returns_zero_when_on_time(): void
    {
        $borrowing = new Borrowing([
            'due_date' => Carbon::now()->addDays(1),
            'return_date' => Carbon::now(),
        ]);

        $this->assertEquals(0, $borrowing->calculateFine());
    }

    public function test_fine_calculation_returns_amount_when_late(): void
    {
        $finePerDay = config('library.fine_per_day', 1000);

        $borrowing = new Borrowing([
            'due_date' => Carbon::now()->subDays(3),
            'return_date' => Carbon::now(),
        ]);

        $this->assertEquals(3 * $finePerDay, $borrowing->calculateFine());
    }

    public function test_borrowing_has_relationships(): void
    {
        $borrowing = new Borrowing();
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $borrowing->student()
        );
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $borrowing->book()
        );
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $borrowing->reminders()
        );
    }

    public function test_fine_amount_accessor_uses_dynamic_calc_when_borrowing(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dipinjam',
            'due_date' => Carbon::now()->subDays(2),
        ]);

        $this->assertGreaterThan(0, $borrowing->fine_amount);
    }

    public function test_fine_amount_accessor_uses_stored_fine_when_returned(): void
    {
        $borrowing = new Borrowing([
            'status' => 'Dikembalikan',
            'fine' => 5000,
            'due_date' => Carbon::now()->subDays(2),
            'return_date' => Carbon::now(),
        ]);

        $this->assertEquals(5000, $borrowing->fine_amount);
    }
}
