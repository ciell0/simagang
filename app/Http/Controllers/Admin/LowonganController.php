<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteLowonganRequest;
use App\Http\Requests\Admin\StoreLowonganRequest;
use App\Models\Lowongan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(): View
    {
        return view('admin.lowongan.index', [
            'lowongan' => Lowongan::query()->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.lowongan.form', ['lowongan' => new Lowongan]);
    }

    public function store(StoreLowonganRequest $request): RedirectResponse
    {
        Lowongan::create($request->validated());

        return to_route('admin.lowongan.index')->with('status', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(Lowongan $lowongan): View
    {
        return view('admin.lowongan.form', ['lowongan' => $lowongan]);
    }

    public function update(StoreLowonganRequest $request, Lowongan $lowongan): RedirectResponse
    {
        $lowongan->update($request->validated());

        return to_route('admin.lowongan.index')->with('status', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(DeleteLowonganRequest $request, Lowongan $lowongan): RedirectResponse
    {
        if ($lowongan->pendaftaran()->exists()) {
            return back()->withErrors([
                'lowongan' => 'Lowongan tidak dapat dihapus karena sudah memiliki pendaftar.',
            ]);
        }

        $lowongan->delete();

        return to_route('admin.lowongan.index')->with('status', 'Lowongan berhasil dihapus.');
    }
}
