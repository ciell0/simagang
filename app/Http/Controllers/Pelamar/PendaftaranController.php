<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelamar\StorePendaftaranRequest;
use App\Models\Lowongan;
use App\Models\Pendaftaran;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function create(Lowongan $lowongan): View|RedirectResponse
    {
        if ($this->lowonganTidakTersedia($lowongan)) {
            return to_route('pelamar.lowongan.show', $lowongan)
                ->withErrors(['lowongan' => 'Lowongan ini sudah ditutup atau melewati batas pendaftaran.']);
        }

        if ($lowongan->pendaftaran()->where('user_id', auth()->id())->exists()) {
            return to_route('pelamar.pendaftaran.index')
                ->with('status', 'Anda sudah mendaftar pada lowongan tersebut.');
        }

        return view('pelamar.pendaftaran.create', ['lowongan' => $lowongan]);
    }

    public function store(StorePendaftaranRequest $request, Lowongan $lowongan): RedirectResponse
    {
        if ($this->lowonganTidakTersedia($lowongan)) {
            return to_route('pelamar.lowongan.show', $lowongan)
                ->withErrors(['lowongan' => 'Lowongan ini sudah ditutup atau melewati batas pendaftaran.']);
        }

        if ($lowongan->pendaftaran()->where('user_id', auth()->id())->exists()) {
            return to_route('pelamar.pendaftaran.index')
                ->with('status', 'Anda sudah mendaftar pada lowongan tersebut.');
        }

        $cvPath = $request->file('cv')->store('cv', 'public');

        try {
            Pendaftaran::create([
                ...$request->safe()->except('cv'),
                'user_id' => $request->user()->id,
                'lowongan_id' => $lowongan->id,
                'cv_path' => $cvPath,
            ]);
        } catch (QueryException $exception) {
            Storage::disk('public')->delete($cvPath);

            if (in_array((string) $exception->getCode(), ['23000', '19'], true)) {
                return to_route('pelamar.pendaftaran.index')
                    ->with('status', 'Pendaftaran untuk lowongan ini sudah tercatat.');
            }

            throw $exception;
        }

        return to_route('pelamar.pendaftaran.index')->with('status', 'Pendaftaran berhasil dikirim.');
    }

    public function index(): View
    {
        return view('pelamar.pendaftaran.index', [
            'pendaftaran' => Pendaftaran::query()
                ->with('lowongan')
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    }

    private function lowonganTidakTersedia(Lowongan $lowongan): bool
    {
        return $lowongan->status !== 'buka'
            || Carbon::parse($lowongan->deadline)->lessThan(today());
    }
}
