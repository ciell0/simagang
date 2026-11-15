<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelamar\FilterLowonganRequest;
use App\Models\Lowongan;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(FilterLowonganRequest $request): View
    {
        $filters = $request->validated();
        $query = Lowongan::query()
            ->where('status', 'buka')
            ->whereDate('deadline', '>=', today());

        if (! empty($filters['divisi'])) {
            $query->where('divisi', $filters['divisi']);
        }

        if (! empty($filters['q'])) {
            $query->where('judul', 'like', '%'.$filters['q'].'%');
        }

        return view('pelamar.lowongan.index', [
            'lowongan' => $query->latest()->paginate(9)->withQueryString(),
            'divisi' => Lowongan::query()->select('divisi')->distinct()->orderBy('divisi')->pluck('divisi'),
            'filters' => $filters,
        ]);
    }

    public function show(Lowongan $lowongan): View
    {
        return view('pelamar.lowongan.show', [
            'lowongan' => $lowongan,
            'sudahMendaftar' => $lowongan->pendaftaran()
                ->where('user_id', auth()->id())
                ->exists(),
            'dapatMendaftar' => $lowongan->status === 'buka'
                && Carbon::parse($lowongan->deadline)->greaterThanOrEqualTo(today()),
        ]);
    }
}
