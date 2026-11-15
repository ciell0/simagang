<?php

namespace Tests\Feature;

use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelamarLowonganTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelamar_can_search_filter_and_paginate_lowongan(): void
    {
        $pelamar = User::factory()->create();

        foreach (range(1, 10) as $nomor) {
            Lowongan::create([
                'judul' => "Analis Data {$nomor}",
                'deskripsi' => 'Mendukung tim data.',
                'divisi' => 'Data',
                'kuota' => 2,
                'deadline' => today()->addDays(10),
                'status' => 'buka',
            ]);
        }

        Lowongan::create([
            'judul' => 'Desainer Produk',
            'deskripsi' => 'Mendukung tim desain.',
            'divisi' => 'Desain',
            'kuota' => 1,
            'deadline' => today()->addDays(10),
            'status' => 'buka',
        ]);

        $this->actingAs($pelamar)
            ->get(route('pelamar.lowongan.index', ['q' => 'Analis', 'divisi' => 'Data']))
            ->assertOk()
            ->assertViewHas('lowongan', fn ($lowongan) => $lowongan->total() === 10
                && $lowongan->count() === 9);
    }

    public function test_pelamar_cannot_start_registration_for_a_closed_or_expired_lowongan(): void
    {
        $pelamar = User::factory()->create();
        $closed = Lowongan::create([
            'judul' => 'Posisi Ditutup',
            'deskripsi' => 'Tidak menerima pendaftar.',
            'divisi' => 'Operasional',
            'kuota' => 1,
            'deadline' => today()->addDays(5),
            'status' => 'tutup',
        ]);
        $expired = Lowongan::create([
            'judul' => 'Posisi Kedaluwarsa',
            'deskripsi' => 'Batas pendaftaran lewat.',
            'divisi' => 'Operasional',
            'kuota' => 1,
            'deadline' => today()->subDay(),
            'status' => 'buka',
        ]);

        $this->actingAs($pelamar)
            ->get(route('pelamar.pendaftaran.create', $closed))
            ->assertRedirect(route('pelamar.lowongan.show', $closed))
            ->assertSessionHasErrors('lowongan');

        $this->get(route('pelamar.pendaftaran.create', $expired))
            ->assertRedirect(route('pelamar.lowongan.show', $expired))
            ->assertSessionHasErrors('lowongan');
    }
}
