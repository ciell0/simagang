<?php

namespace App\Observers;

use App\Models\Pendaftaran;
use App\Notifications\EmailNotifikasi;
use App\Notifications\TelegramNotifikasi;

class PendaftaranObserver
{
    public function updated(Pendaftaran $pendaftaran): void
    {
        if (! $pendaftaran->wasChanged('status')) {
            return;
        }

        foreach ([new EmailNotifikasi, new TelegramNotifikasi] as $notifikasi) {
            $notifikasi->kirim($pendaftaran);
        }
    }
}
