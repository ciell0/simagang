<?php

namespace Tests\Feature;

use App\Models\Lowongan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelamar_can_submit_a_pdf_application(): void
    {
        Storage::fake('public');
        $pelamar = User::factory()->create();
        $lowongan = $this->buatLowongan();

        $this->actingAs($pelamar)
            ->post(route('pelamar.pendaftaran.store', $lowongan), [
                'ipk' => '3.50',
                'semester' => 5,
                'pengalaman_organisasi' => 12,
                'cv' => UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\nSIMAGANG\n%%EOF"),
            ])
            ->assertRedirect(route('pelamar.pendaftaran.index'))
            ->assertSessionHas('status', 'Pendaftaran berhasil dikirim.');

        $this->assertDatabaseHas('pendaftaran', [
            'user_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'status' => 'diproses',
        ]);

        $pendaftaran = Pendaftaran::query()->firstOrFail();
        $this->assertStringStartsWith('cv/', $pendaftaran->cv_path);
        Storage::disk('public')->assertExists($pendaftaran->cv_path);
    }

    public function test_pelamar_cannot_submit_non_pdf_or_duplicate_applications(): void
    {
        Storage::fake('public');
        $pelamar = User::factory()->create();
        $lowongan = $this->buatLowongan();
        $route = route('pelamar.pendaftaran.store', $lowongan);
        $data = [
            'ipk' => '3.50',
            'semester' => 5,
            'pengalaman_organisasi' => 0,
        ];

        $this->actingAs($pelamar)
            ->post($route, [...$data, 'cv' => UploadedFile::fake()->create('surat.txt', 10, 'text/plain')])
            ->assertSessionHasErrors('cv');

        Pendaftaran::create([
            'user_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'cv_path' => 'cv/tersimpan.pdf',
            ...$data,
        ]);

        $this->post($route, [
            ...$data,
            'cv' => UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\nSIMAGANG\n%%EOF"),
        ])->assertRedirect(route('pelamar.pendaftaran.index'));

        $this->assertSame(1, Pendaftaran::query()->count());
        $this->assertSame([], Storage::disk('public')->allFiles('cv'));
    }

    public function test_cv_larger_than_two_megabytes_is_rejected(): void
    {
        Storage::fake('public');
        $pelamar = User::factory()->create();
        $lowongan = $this->buatLowongan();
        $content = "%PDF-1.4\n".str_repeat('0', 2 * 1024 * 1024)."\n%%EOF";

        $this->actingAs($pelamar)
            ->post(route('pelamar.pendaftaran.store', $lowongan), [
                'ipk' => '3.50',
                'semester' => 5,
                'pengalaman_organisasi' => 0,
                'cv' => UploadedFile::fake()->createWithContent('cv.pdf', $content),
            ])
            ->assertSessionHasErrors('cv');

        $this->assertDatabaseCount('pendaftaran', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('cv'));
    }

    public function test_tracking_displays_only_the_authenticated_applicants_records(): void
    {
        $pemilik = User::factory()->create();
        $pelamarLain = User::factory()->create();
        $lowongan = $this->buatLowongan();
        Pendaftaran::create([
            'user_id' => $pemilik->id,
            'lowongan_id' => $lowongan->id,
            'cv_path' => 'cv/rahasia.pdf',
            'ipk' => 3.5,
            'semester' => 5,
            'pengalaman_organisasi' => 0,
        ]);

        $this->actingAs($pelamarLain)
            ->get(route('pelamar.pendaftaran.index'))
            ->assertOk()
            ->assertViewHas('pendaftaran', fn ($pendaftaran) => $pendaftaran->total() === 0);
    }

    private function buatLowongan(): Lowongan
    {
        return Lowongan::create([
            'judul' => 'Pengembang Web',
            'deskripsi' => 'Mendukung pengembangan aplikasi.',
            'divisi' => 'Teknologi Informasi',
            'kuota' => 2,
            'deadline' => today()->addDays(10),
            'status' => 'buka',
        ]);
    }
}
