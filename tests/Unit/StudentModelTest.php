<?php

namespace Tests\Unit;

use App\Models\Student;
use Tests\TestCase;

class StudentModelTest extends TestCase
{
    public function test_avatar_accessor_returns_default_when_null(): void
    {
        $student = new Student(['avatar' => null]);
        $this->assertEquals('default-avatar.png', $student->avatar);
    }

    public function test_avatar_accessor_returns_value_when_set(): void
    {
        $student = new Student(['avatar' => 'uploads/avatar.jpg']);
        $this->assertEquals('uploads/avatar.jpg', $student->avatar);
    }

    public function test_avatar_accessor_returns_default_when_empty_string(): void
    {
        $student = new Student(['avatar' => '']);
        $this->assertEquals('default-avatar.png', $student->avatar);
    }

    public function test_student_has_relationships(): void
    {
        $student = new Student();
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $student->borrowings()
        );
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $student->bookComments()
        );
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $student->testimonials()
        );
    }

    public function test_is_active_flag_returns_bool(): void
    {
        $active = new Student(['is_active' => 'Aktif']);
        $this->assertTrue($active->is_active_flag);

        $inactive = new Student(['is_active' => 'Nonaktif']);
        $this->assertFalse($inactive->is_active_flag);
    }
}
