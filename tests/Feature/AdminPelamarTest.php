<?php

namespace Tests\Feature;

use App\Models\Lowongan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPelamarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_rekap_reads_from_the_database_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $pelamar = User::factory()->create(['name' => 'Pelamar Rekap']);
        $lowongan = $this->buatLowongan(2);

        $lowongan->pendaftaran()->create([
            'user_id' => $pelamar->id,
            'cv_path' => 'cv/rekap.pdf',
            'ipk' => 3.75,
            'semester' => 6,
            'pengalaman_organisasi' => 12,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pelamar.index'))
            ->assertOk()
            ->assertViewHas('rekap', fn ($rekap) => $rekap->total() === 1
                && $rekap->first()->nama === 'Pelamar Rekap'
                && $rekap->first()->judul_lowongan === $lowongan->judul);
    }

    public function test_accepting_a_pelamar_uses_a_transaction_to_update_status_and_quota(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $pelamar = User::factory()->create();
        $lowongan = $this->buatLowongan(2);
        $pendaftaran = Pendaftaran::create([
            'user_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'cv_path' => 'cv/transaksi.pdf',
            'ipk' => 3.5,
            'semester' => 5,
            'pengalaman_organisasi' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.pendaftaran.terima', $pendaftaran))
            ->assertRedirect(route('admin.pelamar.index'));

        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'diterima',
        ]);
        $this->assertDatabaseHas('lowongan', [
            'id' => $lowongan->id,
            'kuota' => 1,
        ]);
    }

    public function test_acceptance_rolls_back_when_no_quota_remains(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $pelamar = User::factory()->create();
        $lowongan = $this->buatLowongan(0);
        $pendaftaran = Pendaftaran::create([
            'user_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'cv_path' => 'cv/kuota-habis.pdf',
            'ipk' => 3.5,
            'semester' => 5,
            'pengalaman_organisasi' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.pendaftaran.terima', $pendaftaran))
            ->assertSessionHasErrors('pendaftaran');

        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'diproses',
        ]);
        $this->assertDatabaseHas('lowongan', [
            'id' => $lowongan->id,
            'kuota' => 0,
        ]);
    }

    private function buatLowongan(int $kuota): Lowongan
    {
        return Lowongan::create([
            'judul' => 'Posisi Uji Transaksi',
            'deskripsi' => 'Deskripsi untuk tes view dan transaksi.',
            'divisi' => 'Teknologi Informasi',
            'kuota' => $kuota,
            'deadline' => today()->addDays(10),
            'status' => 'buka',
        ]);
    }
}
