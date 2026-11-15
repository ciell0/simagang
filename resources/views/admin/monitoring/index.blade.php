@extends('layouts.admin')

@section('judul', 'Monitoring Sistem')
@section('keterangan', 'Pantau status sumber daya server dan performa aplikasi.')

@section('konten')
    <section class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Memori terpakai</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['memory_usage_mb'], 2) }} MB</p>
            </div>
            <div class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Batas memori</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['memory_limit_mb'], 2) }} MB</p>
            </div>
            <div class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Ruang disk gratis</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['disk_free_mb'], 2) }} MB</p>
            </div>
            <div class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Waktu aktif</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($metrics['uptime_seconds']) }} detik</p>
            </div>
        </div>

        <div class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Ekspor laporan</h2>
                    <p class="text-sm text-slate-600">Unduh ringkasan sistem dalam format PDF atau Excel.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.monitoring.export-pdf') }}" class="inline-flex rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Ekspor PDF</a>
                    <a href="{{ route('admin.monitoring.export-excel') }}" class="inline-flex rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Ekspor Excel</a>
                </div>
            </div>
        </div>
    </section>
@endsection
