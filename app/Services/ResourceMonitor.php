<?php

namespace App\Services;

class ResourceMonitor
{
    public function ringkas(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $diskFree = disk_free_space(base_path()) ?: 0;

        return [
            'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'memory_limit_mb' => round($memoryLimit / 1024 / 1024, 2),
            'disk_free_mb' => round($diskFree / 1024 / 1024, 2),
            'uptime_seconds' => $this->uptime(),
        ];
    }

    protected function parseMemoryLimit(string $value): int
    {
        if ($value === '-1') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) rtrim($value, 'kmg');

        return match ($unit) {
            'g' => (int) ($number * 1024 * 1024 * 1024),
            'm' => (int) ($number * 1024 * 1024),
            'k' => (int) ($number * 1024),
            default => (int) $number,
        };
    }

    protected function uptime(): int
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $output = @shell_exec('systeminfo');

            if (is_string($output) && preg_match('/System Up Time:\s+(.*)/i', $output, $matches)) {
                return $this->parseWindowsUptime($matches[1]);
            }

            return 0;
        }

        $uptime = @file_get_contents('/proc/uptime');

        if (! is_string($uptime)) {
            return 0;
        }

        return (int) floor((float) strtok($uptime, ' '));
    }

    protected function parseWindowsUptime(string $value): int
    {
        $parts = preg_split('/\s+/', trim($value));
        $seconds = 0;

        foreach (array_chunk($parts, 2) as $pair) {
            if (count($pair) !== 2) {
                continue;
            }

            [$count, $unit] = $pair;
            $multiplier = match (strtolower($unit)) {
                'days' => 86400,
                'hours' => 3600,
                'minutes' => 60,
                'seconds' => 1,
                default => 0,
            };

            $seconds += (int) $count * $multiplier;
        }

        return $seconds;
    }
}
