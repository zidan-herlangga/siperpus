<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class PanelAccessTest extends TestCase
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

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/staff')->assertRedirect('/staff/login');
        $this->get('/kepsek')->assertRedirect('/kepsek/login');
    }

    public function test_guest_can_see_login_pages(): void
    {
        $this->get('/admin/login')->assertStatus(200);
        $this->get('/staff/login')->assertStatus(200);
        $this->get('/kepsek/login')->assertStatus(200);
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $this->actingAs($this->admin, 'web')
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_staff_cannot_access_admin_panel(): void
    {
        $this->actingAs($this->staff, 'web')
            ->get('/admin')
            ->assertStatus(403);
    }

    public function test_kepsek_cannot_access_admin_panel(): void
    {
        $this->actingAs($this->kepsek, 'web')
            ->get('/admin')
            ->assertStatus(403);
    }

    public function test_staff_can_access_staff_panel(): void
    {
        $this->actingAs($this->staff, 'web')
            ->get('/staff')
            ->assertStatus(200);
    }

    public function test_admin_cannot_access_staff_panel(): void
    {
        $this->actingAs($this->admin, 'web')
            ->get('/staff')
            ->assertStatus(403);
    }

    public function test_kepsek_can_access_kepsek_panel(): void
    {
        $this->actingAs($this->kepsek, 'web')
            ->get('/kepsek')
            ->assertStatus(200);
    }

    public function test_admin_cannot_access_kepsek_panel(): void
    {
        $this->actingAs($this->admin, 'web')
            ->get('/kepsek')
            ->assertStatus(403);
    }
}
