<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ResourceMonitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Fase5Test extends TestCase
{
    use RefreshDatabase;

    public function test_monitoring_service_collects_key_system_metrics(): void
    {
        $monitor = app(ResourceMonitor::class);

        $data = $monitor->ringkas();

        $this->assertArrayHasKey('memory_usage_mb', $data);
        $this->assertArrayHasKey('memory_limit_mb', $data);
        $this->assertArrayHasKey('disk_free_mb', $data);
        $this->assertArrayHasKey('uptime_seconds', $data);
    }

    public function test_admin_can_access_monitoring_dashboard_and_export_files(): void
    {
        $admin = User::create([
            'name' => 'Admin Monitoring',
            'email' => 'monitoring@simagang.test',
            'role' => 'admin',
            'no_hp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.monitoring.export-pdf'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->actingAs($admin)
            ->get(route('admin.monitoring.export-excel'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
