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

        $textResponse = $this->actingAs($admin)
            ->get(route('admin.monitoring.export-txt'));

        $textResponse
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="simagang-monitoring.txt"')
            ->assertSeeText('SIMAGANG Monitoring');
        $this->assertStringStartsWith('text/plain', $textResponse->baseResponse->headers->get('Content-Type'));

        $csvResponse = $this->actingAs($admin)
            ->get(route('admin.monitoring.export-csv'));

        $csvResponse
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="simagang-monitoring.csv"')
            ->assertSeeText('memory_usage_mb');
        $this->assertStringStartsWith('text/csv', $csvResponse->baseResponse->headers->get('Content-Type'));
    }
}
