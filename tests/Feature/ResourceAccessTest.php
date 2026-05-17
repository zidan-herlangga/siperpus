<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Filament\Resources\Books\BookResource;
use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Filament\Resources\Students\StudentResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Reminders\ReminderResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use Tests\TestCase;

class ResourceAccessTest extends TestCase
{
    private Admin $admin;
    private Admin $staff;
    private Admin $kepsek;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $this->staff = Admin::firstOrCreate(
            ['email' => 'staff@school.com'],
            ['name' => 'Staff', 'password' => bcrypt('password'), 'role' => 'staff']
        );

        $this->kepsek = Admin::firstOrCreate(
            ['email' => 'kepsek@school.com'],
            ['name' => 'Kepsek', 'password' => bcrypt('password'), 'role' => 'kepsek']
        );
    }

    public function test_admin_can_view_all_resources(): void
    {
        $this->actingAs($this->admin, 'web');

        $this->assertTrue(BookResource::canViewAny());
        $this->assertTrue(BorrowingResource::canViewAny());
        $this->assertTrue(StudentResource::canViewAny());
        $this->assertTrue(CategoryResource::canViewAny());
        $this->assertTrue(ReminderResource::canViewAny());
        $this->assertTrue(TestimonialResource::canViewAny());
    }

    public function test_admin_can_create_all_resources(): void
    {
        $this->actingAs($this->admin, 'web');

        $this->assertTrue(BookResource::canCreate());
        $this->assertTrue(BorrowingResource::canCreate());
        $this->assertTrue(StudentResource::canCreate());
        $this->assertTrue(CategoryResource::canCreate());
    }

    public function test_admin_can_delete_all_resources(): void
    {
        $this->actingAs($this->admin, 'web');

        $this->assertTrue(BookResource::canDeleteAny());
    }

    public function test_staff_can_view_resources(): void
    {
        $this->actingAs($this->staff, 'web');

        $this->assertTrue(BookResource::canViewAny());
        $this->assertTrue(BorrowingResource::canViewAny());
        $this->assertTrue(StudentResource::canViewAny());
        $this->assertTrue(CategoryResource::canViewAny());
    }

    public function test_staff_can_create_but_not_delete(): void
    {
        $this->actingAs($this->staff, 'web');

        $this->assertTrue(BookResource::canCreate());
        $this->assertFalse(BookResource::canDeleteAny());
    }

    public function test_kepsek_can_view_but_not_create_or_delete(): void
    {
        $this->actingAs($this->kepsek, 'web');

        $this->assertTrue(BookResource::canViewAny());
        $this->assertFalse(BookResource::canCreate());
        $this->assertFalse(BookResource::canDeleteAny());
    }

    public function test_kepsek_cannot_edit(): void
    {
        $this->actingAs($this->kepsek, 'web');

        $book = new \App\Models\Book();
        $this->assertFalse(BookResource::canEdit($book));
    }

    public function test_staff_can_edit(): void
    {
        $this->actingAs($this->staff, 'web');

        $book = new \App\Models\Book();
        $this->assertTrue(BookResource::canEdit($book));
    }
}
