<?php

namespace App\Services;

use App\Models\Pendaftaran;

class SkorService
{
    /**
     * Menghitung skor kandidat berdasarkan kriteria akademik dan pengalaman.
     * Kompleksitas: O(1) karena hanya memproses sejumlah field numerik tetap.
     */
    public function hitung(Pendaftaran|array $pendaftaran): float
    {
        $ipk = (float) ($pendaftaran['ipk'] ?? $pendaftaran->ipk ?? 0.0);
        $semester = (int) ($pendaftaran['semester'] ?? $pendaftaran->semester ?? 0);
        $pengalaman = (int) ($pendaftaran['pengalaman_organisasi'] ?? $pendaftaran->pengalaman_organisasi ?? 0);

        $nilai = ($ipk * 70.0) + ((14 - min($semester, 14)) * 2.5) + ($pengalaman * 0.35);

        return round($nilai, 2);
    }
}
