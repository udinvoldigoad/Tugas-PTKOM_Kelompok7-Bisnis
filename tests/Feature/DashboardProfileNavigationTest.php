<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardProfileNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_route_displays_sales_dashboard_and_activates_dashboard_navigation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Performa Penjualan Kasir')
            ->assertDontSee('Kelola Informasi Akun Anda')
            ->assertSee('class="sidebar-item is-active" aria-label="Dashboard"', escape: false)
            ->assertSee('href="'.route('profile.edit').'"', escape: false);
    }

    public function test_profile_route_displays_profile_page_and_preserves_sidebar_navigation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Kelola Informasi Akun Anda')
            ->assertDontSee('Performa Penjualan Kasir')
            ->assertSee('class="sidebar-item is-active" aria-label="Profil"', escape: false)
            ->assertSee('href="'.route('dashboard').'"', escape: false);
    }
}
