<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendPageTest extends TestCase
{
    public function test_home_page_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_book_catalog_page_returns_200(): void
    {
        $response = $this->get('/books');
        $response->assertStatus(200);
    }

    public function test_student_login_page_returns_200(): void
    {
        $response = $this->get('/login-student');
        $response->assertStatus(200);
    }

    public function test_admin_login_page_returns_200(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_staff_login_page_returns_200(): void
    {
        $response = $this->get('/staff/login');
        $response->assertStatus(200);
    }

    public function test_kepsek_login_page_returns_200(): void
    {
        $response = $this->get('/kepsek/login');
        $response->assertStatus(200);
    }
}
