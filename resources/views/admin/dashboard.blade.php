@extends('layouts.admin')

@section('judul', 'Dasbor Admin')
@section('keterangan', 'Selamat datang di panel administrator SIMAGANG.')

@section('konten')
    <section class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Pengelolaan SIMAGANG</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Kelola posisi magang dan tinjau rekap pendaftar dari panel administrator.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('admin.lowongan.index') }}" class="inline-flex rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Kelola lowongan</a>
            <a href="{{ route('admin.monitoring.index') }}" class="inline-flex rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Monitoring sistem</a>
            <a href="{{ route('admin.pelamar.index') }}" class="inline-flex rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Lihat rekap pelamar</a>
        </div>
    </section>
@endsection