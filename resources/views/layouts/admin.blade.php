<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim($__env->yieldContent('judul', 'Dasbor')) }} | SIMAGANG Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold tracking-wide text-emerald-800">SIMAGANG <span class="font-normal text-slate-500">/ Administrator</span></a>
            <nav class="flex items-center gap-4 text-sm font-medium">
                <a class="text-slate-700 hover:text-emerald-800" href="{{ route('admin.dashboard') }}">Dasbor</a>
                <a class="text-slate-700 hover:text-emerald-800" href="{{ route('admin.lowongan.index') }}">Lowongan</a>
                <a class="text-slate-700 hover:text-emerald-800" href="{{ route('admin.monitoring.index') }}">Monitoring</a>
                <a class="text-slate-700 hover:text-emerald-800" href="{{ route('admin.pelamar.index') }}">Rekap pelamar</a>
            </nav>
            <div class="flex items-center gap-4 text-sm">
                <span class="hidden text-slate-600 sm:inline">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-md border border-slate-300 px-3 py-2 font-medium hover:bg-slate-50" type="submit">Keluar</button>
                </form>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">@yield('judul', 'Dasbor Admin')</h1>
            <p class="mt-1 text-sm text-slate-600">@yield('keterangan', 'Sistem Informasi Pendaftaran Magang')</p>
        </div>
        @yield('konten')
    </main>
</body>
</html>