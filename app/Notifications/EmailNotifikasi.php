<?php

namespace App\Notifications;

use App\Contracts\NotifikasiInterface;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Log;

class EmailNotifikasi implements NotifikasiInterface
{
    public function kirim(Pendaftaran $pendaftaran): void
    {
        Log::info('SIMAGANG email notification sent', [
            'pendaftaran_id' => $pendaftaran->id,
            'user_id' => $pendaftaran->user_id,
            'status' => $pendaftaran->status,
            'email' => $pendaftaran->user?->email,
        ]);
    }
}
