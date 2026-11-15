@extends('layouts.admin')

@section('judul', $lowongan->exists ? 'Ubah Lowongan' : 'Tambah Lowongan')
@section('keterangan', 'Lengkapi informasi posisi magang dan status pendaftarannya.')

@section('konten')
    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ $lowongan->exists ? route('admin.lowongan.update', $lowongan) : route('admin.lowongan.store') }}" class="max-w-3xl space-y-5 rounded-md border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        @if ($lowongan->exists)
            @method('PUT')
        @endif

        <div>
            <label for="judul" class="mb-1 block text-sm font-medium">Judul lowongan</label>
            <input id="judul" name="judul" value="{{ old('judul', $lowongan->judul) }}" required maxlength="255" class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
            @error('judul')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="deskripsi" class="mb-1 block text-sm font-medium">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="6" required maxlength="10000" class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
            @error('deskripsi')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="divisi" class="mb-1 block text-sm font-medium">Divisi</label>
                <input id="divisi" name="divisi" value="{{ old('divisi', $lowongan->divisi) }}" required maxlength="100" class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                @error('divisi')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kuota" class="mb-1 block text-sm font-medium">Kuota peserta</label>
                <input id="kuota" name="kuota" type="number" min="1" max="65535" value="{{ old('kuota', $lowongan->kuota) }}" required class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                @error('kuota')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="deadline" class="mb-1 block text-sm font-medium">Batas pendaftaran</label>
                <input id="deadline" name="deadline" type="date" min="{{ today()->toDateString() }}" value="{{ old('deadline', $lowongan->deadline?->format('Y-m-d')) }}" required class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                @error('deadline')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="mb-1 block text-sm font-medium">Status lowongan</label>
                <select id="status" name="status" required class="w-full rounded-md border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                    <option value="buka" @selected(old('status', $lowongan->status ?: 'buka') === 'buka')>Dibuka</option>
                    <option value="tutup" @selected(old('status', $lowongan->status) === 'tutup')>Ditutup</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Simpan lowongan</button>
            <a href="{{ route('admin.lowongan.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Batal</a>
        </div>
    </form>
@endsection