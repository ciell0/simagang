@extends('layouts.pelamar')

@section('judul', $lowongan->judul)
@section('keterangan', $lowongan->divisi)

@section('konten')
    @if (session('status'))
        <p class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <article class="max-w-4xl rounded-md border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <dl class="grid gap-4 border-b border-slate-100 pb-5 sm:grid-cols-3">
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Divisi</dt><dd class="mt-1 font-medium">{{ $lowongan->divisi }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Kuota</dt><dd class="mt-1 font-medium">{{ $lowongan->kuota }} peserta</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Batas pendaftaran</dt><dd class="mt-1 font-medium">{{ $lowongan->deadline->format('d/m/Y') }}</dd></div>
        </dl>
        <div class="prose prose-slate mt-6 max-w-none whitespace-pre-line text-sm leading-7">{{ $lowongan->deskripsi }}</div>
        <div class="mt-8 flex flex-wrap gap-3 border-t border-slate-100 pt-5">
            @if ($sudahMendaftar)
                <a href="{{ route('pelamar.pendaftaran.index') }}" class="rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Lihat status pendaftaran</a>
            @elseif ($dapatMendaftar)
                <a href="{{ route('pelamar.pendaftaran.create', $lowongan) }}" class="rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Daftar sekarang</a>
            @else
                <span class="rounded-md bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">Pendaftaran ditutup</span>
            @endif
            <a href="{{ route('pelamar.lowongan.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Kembali ke lowongan</a>
        </div>
    </article>
@endsection