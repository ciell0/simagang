<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_are_redirected_to_the_dashboard_for_their_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $pelamar = User::factory()->create(['role' => 'pelamar']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($pelamar)
            ->get(route('dashboard'))
            ->assertRedirect(route('pelamar.dashboard'));
    }

    public function test_users_cannot_open_another_role_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $pelamar = User::factory()->create(['role' => 'pelamar']);

        $this->actingAs($admin)
            ->get(route('pelamar.dashboard'))
            ->assertForbidden();

        $this->actingAs($pelamar)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
