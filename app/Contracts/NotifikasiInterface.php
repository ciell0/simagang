<?php

namespace App\Contracts;

use App\Models\Pendaftaran;

interface NotifikasiInterface
{
    public function kirim(Pendaftaran $pendaftaran): void;
}
