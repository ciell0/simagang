<?php

namespace Tests\Feature;

use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLowonganTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_lowongan(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $data = [
            'judul' => 'Magang Pengembangan Web',
            'deskripsi' => 'Mendukung pengembangan aplikasi internal.',
            'divisi' => 'Teknologi Informasi',
            'kuota' => 3,
            'deadline' => today()->addDays(14)->toDateString(),
            'status' => 'buka',
        ];

        $this->actingAs($admin)
            ->post(route('admin.lowongan.store'), $data)
            ->assertRedirect(route('admin.lowongan.index'));

        $lowongan = Lowongan::query()->where('judul', $data['judul'])->firstOrFail();
        $this->assertDatabaseHas('lowongan', ['id' => $lowongan->id, 'status' => 'buka']);

        $this->actingAs($admin)
            ->put(route('admin.lowongan.update', $lowongan), [...$data, 'status' => 'tutup'])
            ->assertRedirect(route('admin.lowongan.index'));

        $this->assertDatabaseHas('lowongan', ['id' => $lowongan->id, 'status' => 'tutup']);

        $this->actingAs($admin)
            ->delete(route('admin.lowongan.destroy', $lowongan))
            ->assertRedirect(route('admin.lowongan.index'));

        $this->assertDatabaseMissing('lowongan', ['id' => $lowongan->id]);
    }

    public function test_admin_cannot_delete_a_lowongan_with_applicants(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'nim' => null]);
        $pelamar = User::factory()->create();
        $lowongan = Lowongan::create([
            'judul' => 'Analis Data',
            'deskripsi' => 'Menganalisis data.',
            'divisi' => 'Data',
            'kuota' => 2,
            'deadline' => today()->addDays(7),
            'status' => 'buka',
        ]);
        $lowongan->pendaftaran()->create([
            'user_id' => $pelamar->id,
            'cv_path' => 'cv/demo.pdf',
            'ipk' => 3.25,
            'semester' => 5,
            'pengalaman_organisasi' => 0,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.lowongan.destroy', $lowongan))
            ->assertSessionHasErrors('lowongan');

        $this->assertDatabaseHas('lowongan', ['id' => $lowongan->id]);
    }
}
