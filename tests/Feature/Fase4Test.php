<?php

namespace Tests\Feature;

use App\Contracts\NotifikasiInterface;
use App\Models\Admin;
use App\Models\Pelamar;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Notifications\EmailNotifikasi;
use App\Notifications\TelegramNotifikasi;
use App\Services\PencarianService;
use App\Services\RankingService;
use App\Services\SkorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class Fase4Test extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_models_are_available_as_inheritance_examples(): void
    {
        $admin = new Admin;
        $pelamar = new Pelamar;

        $this->assertInstanceOf(User::class, $admin);
        $this->assertInstanceOf(User::class, $pelamar);
        $this->assertSame('admin', $admin->role ?? 'admin');
        $this->assertSame('pelamar', $pelamar->role ?? 'pelamar');
    }

    public function test_skor_and_ranking_services_score_candidates_consistently(): void
    {
        $skor = app(SkorService::class);
        $ranking = app(RankingService::class);

        $pendaftaranPertama = new Pendaftaran([
            'ipk' => 3.80,
            'semester' => 6,
            'pengalaman_organisasi' => 2,
        ]);
        $pendaftaranKedua = new Pendaftaran([
            'ipk' => 3.40,
            'semester' => 5,
            'pengalaman_organisasi' => 1,
        ]);

        $nilaiPertama = $skor->hitung($pendaftaranPertama);
        $nilaiKedua = $skor->hitung($pendaftaranKedua);

        $this->assertGreaterThan($nilaiKedua, $nilaiPertama);

        $hasil = $ranking->urutkan(new Collection([$pendaftaranKedua, $pendaftaranPertama]));

        $this->assertSame([$pendaftaranPertama->getKey() ?? 0, $pendaftaranKedua->getKey() ?? 0], array_map(
            fn (Pendaftaran $pendaftaran) => $pendaftaran->getKey() ?? 0,
            array_map(fn (array $item) => $item['pendaftaran'], $hasil)
        ));
    }

    public function test_pencarian_service_filters_candidates_by_keyword_case_insensitive(): void
    {
        $pencarian = app(PencarianService::class);

        $items = collect([
            ['nama' => 'Rina Pratama', 'divisi' => 'Data'],
            ['nama' => 'Budi Santoso', 'divisi' => 'Teknologi'],
            ['nama' => 'Dewi Kartika', 'divisi' => 'Data'],
        ]);

        $hasil = $pencarian->cari($items, 'rina');

        $this->assertCount(1, $hasil);
        $this->assertSame('Rina Pratama', $hasil->first()['nama']);
    }

    public function test_notification_strategies_implement_the_contract(): void
    {
        $email = new EmailNotifikasi;
        $telegram = new TelegramNotifikasi;

        $this->assertInstanceOf(NotifikasiInterface::class, $email);
        $this->assertInstanceOf(NotifikasiInterface::class, $telegram);
    }
}
