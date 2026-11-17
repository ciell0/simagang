<?php

namespace App\Models;

use App\Services\SkorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'lowongan_id',
        'cv_path',
        'ipk',
        'semester',
        'pengalaman_organisasi',
        'skor',
        'status',
        'catatan_admin',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $pendaftaran): void {
            $pendaftaran->skor ??= app(SkorService::class)->hitung($pendaftaran);
        });

        static::updating(function (self $pendaftaran): void {
            if ($pendaftaran->isDirty(['ipk', 'semester', 'pengalaman_organisasi'])) {
                $pendaftaran->skor = app(SkorService::class)->hitung($pendaftaran);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'ipk' => 'decimal:2',
            'semester' => 'integer',
            'pengalaman_organisasi' => 'integer',
            'skor' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }
}
