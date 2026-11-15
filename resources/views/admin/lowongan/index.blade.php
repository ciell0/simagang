@extends('layouts.admin')

@section('judul', 'Kelola Lowongan')
@section('keterangan', 'Buat dan perbarui posisi magang yang tersedia.')

@section('konten')
    @if (session('status'))
        <p class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.lowongan.create') }}" class="rounded-md bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Tambah lowongan</a>
    </div>

    <div class="overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Judul</th>
                        <th class="px-4 py-3 font-semibold">Divisi</th>
                        <th class="px-4 py-3 font-semibold">Kuota</th>
                        <th class="px-4 py-3 font-semibold">Batas daftar</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($lowongan as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $item->judul }}</td>
                            <td class="px-4 py-3">{{ $item->divisi }}</td>
                            <td class="px-4 py-3">{{ $item->kuota }}</td>
                            <td class="px-4 py-3">{{ $item->deadline->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $item->status === 'buka' ? 'Dibuka' : 'Ditutup' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a class="font-medium text-emerald-800 hover:underline" href="{{ route('admin.lowongan.edit', $item) }}">Ubah</a>
                                    <form method="POST" action="{{ route('admin.lowongan.destroy', $item) }}" onsubmit="return confirm('Hapus lowongan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="font-medium text-red-700 hover:underline" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-8 text-center text-slate-500" colspan="6">Belum ada lowongan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $lowongan->links() }}</div>
@endsection