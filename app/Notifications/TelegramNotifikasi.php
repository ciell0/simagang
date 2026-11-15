<?php

namespace App\Notifications;

use App\Contracts\NotifikasiInterface;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Log;

class TelegramNotifikasi implements NotifikasiInterface
{
    public function kirim(Pendaftaran $pendaftaran): void
    {
        Log::info('SIMAGANG telegram notification sent', [
            'pendaftaran_id' => $pendaftaran->id,
            'user_id' => $pendaftaran->user_id,
            'status' => $pendaftaran->status,
            'lowongan_id' => $pendaftaran->lowongan_id,
        ]);
    }
}
