<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ResourceMonitor;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function __construct(protected ResourceMonitor $monitor) {}

    public function index(): View
    {
        return view('admin.monitoring.index', [
            'metrics' => $this->monitor->ringkas(),
        ]);
    }

    public function exportTxt(): Response
    {
        $metrics = $this->monitor->ringkas();

        $content = implode("\n", [
            'SIMAGANG Monitoring',
            '-------------------',
            'Memory usage: '.$metrics['memory_usage_mb'].' MB',
            'Memory limit: '.$metrics['memory_limit_mb'].' MB',
            'Disk free: '.$metrics['disk_free_mb'].' MB',
            'Uptime: '.$metrics['uptime_seconds'].' detik',
        ]);

        return response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="simagang-monitoring.txt"',
        ]);
    }

    public function exportCsv(): Response
    {
        $metrics = $this->monitor->ringkas();

        $content = "Nama;Nilai\n"
            .'memory_usage_mb;'.$metrics['memory_usage_mb']."\n"
            .'memory_limit_mb;'.$metrics['memory_limit_mb']."\n"
            .'disk_free_mb;'.$metrics['disk_free_mb']."\n"
            .'uptime_seconds;'.$metrics['uptime_seconds']."\n";

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="simagang-monitoring.csv"',
        ]);
    }
}
