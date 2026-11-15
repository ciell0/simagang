@extends('layouts.pelamar')

@section('judul', 'Pendaftaran Saya')
@section('keterangan', 'Pantau status setiap pendaftaran magang Anda.')

@section('konten')
    @if (session('status'))
        <p class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('status') }}</p>
    @endif

    <div class="overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Lowongan</th>
                        <th class="px-4 py-3 font-semibold">Tanggal daftar</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pendaftaran as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <a class="font-semibold text-emerald-800 hover:underline" href="{{ route('pelamar.lowongan.show', $item->lowongan) }}">{{ $item->lowongan->judul }}</a>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->lowongan->divisi }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                @php($labelStatus = ['diproses' => 'Diproses', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'][$item->status])
                                <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-semibold {{ $item->status === 'diterima' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-900') }}">{{ $labelStatus }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->catatan_admin ?: 'Belum ada catatan.' }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-8 text-center text-slate-500" colspan="4">Anda belum mengirim pendaftaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $pendaftaran->links() }}</div>
@endsection