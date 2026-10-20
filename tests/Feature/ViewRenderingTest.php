<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewRenderingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test root url renders home landing page.
     */
    public function test_root_renders_home_landing_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SafeSpace');
        $response->assertSee('Hentikan Perundungan');
    }

    /**
     * Test user login page renders successfully.
     */
    public function test_user_login_page_renders(): void
    {
        $response = $this->get(route('user.login'));
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee('Ocean Dusk');
    }

    /**
     * Test user register page renders successfully.
     */
    public function test_user_register_page_renders(): void
    {
        $response = $this->get(route('user.register'));
        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
    }

    /**
     * Test user beranda page renders successfully.
     */
    public function test_user_beranda_page_renders(): void
    {
        $response = $this->get(route('user.beranda'));
        $response->assertStatus(200);
        $response->assertSee('Ruang Aman');
        $response->assertSee('Ceritakan Sekarang');
    }

    /**
     * Test user riwayat page renders successfully.
     */
    public function test_user_riwayat_page_renders(): void
    {
        $response = $this->get(route('user.riwayat'));
        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas Laporan');
    }

    /**
     * Test user profile page renders successfully.
     */
    public function test_user_profile_page_renders(): void
    {
        $response = $this->get(route('user.profile'));
        $response->assertStatus(200);
        $response->assertSee('Pusat Bantuan');
        $response->assertSee('Pusat Konseling');
    }

    /**
     * Test admin root url renders admin login page.
     */
    public function test_admin_root_renders_admin_login(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Portal Guru BK', false);
    }

    /**
     * Test admin login page renders successfully.
     */
    public function test_admin_login_page_renders(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Portal Guru BK', false);
    }

    /**
     * Test admin dashboard home page renders successfully.
     */
    public function test_admin_dashboard_home_page_renders(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('SafeVoice');
        $response->assertSee('Ringkasan');
        $response->assertSee('Status Penanganan Laporan');
    }
}
