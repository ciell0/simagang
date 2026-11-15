@extends('layouts.pelamar')

@section('judul', 'Dasbor Pelamar')
@section('keterangan', 'Selamat datang di Sistem Informasi Pendaftaran Magang.')

@section('konten')
    <section class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Halo, {{ Auth::user()->name }}</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Temukan posisi magang yang sesuai dan pantau perkembangan pendaftaran Anda dari satu tempat.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('pelamar.lowongan.index') }}" class="inline-flex rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Lihat lowongan</a>
            <a href="{{ route('pelamar.pendaftaran.index') }}" class="inline-flex rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Pendaftaran saya</a>
        </div>
    </section>
@endsection