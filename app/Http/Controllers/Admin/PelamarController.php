<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TerimaPendaftaranRequest;
use App\Models\Lowongan;
use App\Models\Pendaftaran;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PelamarController extends Controller
{
    public function index(): View
    {
        return view('admin.pelamar.index', [
            'rekap' => DB::table('v_rekap_pelamar')
                ->orderByDesc('tanggal_pendaftaran')
                ->paginate(15),
        ]);
    }

    public function terima(
        TerimaPendaftaranRequest $request,
        Pendaftaran $pendaftaran
    ): RedirectResponse {
        try {
            DB::transaction(function () use ($pendaftaran): void {
                $pendaftaran = Pendaftaran::query()
                    ->whereKey($pendaftaran->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($pendaftaran->status !== 'diproses') {
                    throw new DomainException('Pendaftaran ini tidak lagi berstatus diproses.');
                }

                $lowongan = Lowongan::query()
                    ->whereKey($pendaftaran->lowongan_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lowongan->kuota < 1) {
                    throw new DomainException('Kuota lowongan sudah habis.');
                }

                $pendaftaran->status = 'diterima';
                $pendaftaran->save();
                $lowongan->decrement('kuota');
            });
        } catch (DomainException $exception) {
            return back()->withErrors(['pendaftaran' => $exception->getMessage()]);
        }

        return to_route('admin.pelamar.index')->with('status', 'Pelamar berhasil diterima dan kuota lowongan telah diperbarui.');
    }
}
