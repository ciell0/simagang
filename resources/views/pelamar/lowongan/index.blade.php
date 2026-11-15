@extends('layouts.pelamar')

@section('judul', 'Daftar Lowongan')
@section('keterangan', 'Cari posisi magang yang masih menerima pendaftaran.')

@section('konten')
    <form method="GET" action="{{ route('pelamar.lowongan.index') }}" class="mb-6 grid gap-3 rounded-md border border-slate-200 bg-white p-4 sm:grid-cols-[1fr_220px_auto]">
        <label class="sr-only" for="q">Cari judul lowongan</label>
        <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari judul lowongan" class="rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
        <label class="sr-only" for="divisi">Pilih divisi</label>
        <select id="divisi" name="divisi" class="rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
            <option value="">Semua divisi</option>
            @foreach ($divisi as $namaDivisi)
                <option value="{{ $namaDivisi }}" @selected(($filters['divisi'] ?? '') === $namaDivisi)>{{ $namaDivisi }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Cari</button>
            <a href="{{ route('pelamar.lowongan.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($lowongan as $item)
            <article class="flex h-full flex-col rounded-md border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-emerald-800">{{ $item->divisi }}</p>
                <h2 class="mt-2 text-lg font-semibold">{{ $item->judul }}</h2>
                <p class="mt-2 line-clamp-3 flex-1 text-sm leading-6 text-slate-600">{{ $item->deskripsi }}</p>
                <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4 text-sm">
                    <span class="text-slate-600">Batas {{ $item->deadline->format('d/m/Y') }}</span>
                    <a href="{{ route('pelamar.lowongan.show', $item) }}" class="font-semibold text-emerald-800 hover:underline">Lihat detail</a>
                </div>
            </article>
        @empty
            <p class="rounded-md border border-slate-200 bg-white p-6 text-sm text-slate-600 md:col-span-2 xl:col-span-3">Tidak ada lowongan yang sesuai dengan pencarian Anda.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $lowongan->links() }}</div>
@endsection