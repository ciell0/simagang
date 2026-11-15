@extends('layouts.pelamar')

@section('judul', 'Formulir Pendaftaran')
@section('keterangan', $lowongan->judul.' / '.$lowongan->divisi)

@section('konten')
    @if ($errors->any())
        <div class="mb-4 max-w-3xl rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('pelamar.pendaftaran.store', $lowongan) }}" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-md border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        <div class="rounded-md bg-slate-50 p-4 text-sm">
            <p class="font-semibold">{{ $lowongan->judul }}</p>
            <p class="mt-1 text-slate-600">Batas pendaftaran {{ $lowongan->deadline->format('d/m/Y') }}</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="ipk" class="mb-1 block text-sm font-medium">IPK</label>
                <input id="ipk" name="ipk" type="number" min="0" max="4" step="0.01" value="{{ old('ipk') }}" required class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                @error('ipk')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="semester" class="mb-1 block text-sm font-medium">Semester saat ini</label>
                <input id="semester" name="semester" type="number" min="1" max="14" value="{{ old('semester') }}" required class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                @error('semester')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label for="pengalaman_organisasi" class="mb-1 block text-sm font-medium">Pengalaman organisasi (bulan)</label>
            <input id="pengalaman_organisasi" name="pengalaman_organisasi" type="number" min="0" max="255" value="{{ old('pengalaman_organisasi', 0) }}" required class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
            @error('pengalaman_organisasi')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="cv" class="mb-1 block text-sm font-medium">Berkas CV (PDF, maksimal 2 MB)</label>
            <input id="cv" name="cv" type="file" accept="application/pdf,.pdf" required class="block w-full rounded-md border border-slate-300 text-sm file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2 focus:border-emerald-700 focus:ring-emerald-700">
            @error('cv')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Kirim pendaftaran</button>
            <a href="{{ route('pelamar.lowongan.show', $lowongan) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Batal</a>
        </div>
    </form>
@endsection