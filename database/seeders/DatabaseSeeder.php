<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demoPassword = config('simagang.demo_password');

        if (! is_string($demoPassword) || mb_strlen($demoPassword) < 8) {
            throw new RuntimeException('Isi SIMAGANG_DEMO_PASSWORD dengan minimal 8 karakter sebelum menjalankan seeder.');
        }

        User::updateOrCreate(
            ['email' => 'admin@simagang.test'],
            [
                'name' => 'Administrator SIMAGANG',
                'role' => 'admin',
                'nim' => null,
                'no_hp' => '081200000001',
                'password' => Hash::make($demoPassword),
            ]
        );

        foreach (range(1, 5) as $nomor) {
            User::updateOrCreate(
                ['email' => "pelamar{$nomor}@simagang.test"],
                [
                    'name' => "Pelamar Demo {$nomor}",
                    'role' => 'pelamar',
                    'nim' => '2026'.str_pad((string) $nomor, 6, '0', STR_PAD_LEFT),
                    'no_hp' => '08120000000'.($nomor + 1),
                    'password' => Hash::make($demoPassword),
                ]
            );
        }

        $lowongan = [
            ['judul' => 'Asisten Pengembangan Web', 'divisi' => 'Teknologi Informasi', 'kuota' => 3],
            ['judul' => 'Analis Data', 'divisi' => 'Data dan Analitik', 'kuota' => 2],
            ['judul' => 'Desainer Antarmuka', 'divisi' => 'Desain Produk', 'kuota' => 2],
            ['judul' => 'Staf Komunikasi', 'divisi' => 'Hubungan Masyarakat', 'kuota' => 2],
            ['judul' => 'Asisten Administrasi', 'divisi' => 'Administrasi', 'kuota' => 4],
            ['judul' => 'Teknisi Infrastruktur', 'divisi' => 'Teknologi Informasi', 'kuota' => 1],
        ];

        foreach ($lowongan as $data) {
            DB::table('lowongan')->updateOrInsert(
                ['judul' => $data['judul']],
                [
                    ...$data,
                    'deskripsi' => 'Program magang untuk mendukung kegiatan tim dan memperoleh pengalaman kerja profesional.',
                    'deadline' => now()->addDays(30)->toDateString(),
                    'status' => 'buka',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
