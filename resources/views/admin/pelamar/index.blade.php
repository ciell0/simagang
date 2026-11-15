@extends('layouts.admin')

@section('judul', 'Rekap Pelamar')
@section('keterangan', 'Data ditampilkan dari view v_rekap_pelamar.')

@section('konten')
    @if (session('status'))
        <p class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Pelamar</th>
                        <th class="px-4 py-3 font-semibold">Lowongan</th>
                        <th class="px-4 py-3 font-semibold">IPK</th>
                        <th class="px-4 py-3 font-semibold">Semester</th>
                        <th class="px-4 py-3 font-semibold">Skor</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rekap as $baris)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-semibold">{{ $baris->nama }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $baris->nim }} · {{ $baris->email }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $baris->judul_lowongan }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $baris->divisi }}</p>
                            </td>
                            <td class="px-4 py-3">{{ number_format((float) $baris->ipk, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $baris->semester }}</td>
                            <td class="px-4 py-3">{{ $baris->skor === null ? 'Belum dihitung' : number_format((float) $baris->skor, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ ucfirst($baris->status) }}</td>
                            <td class="px-4 py-3">
                                @if ($baris->status === 'diproses')
                                    <form method="POST" action="{{ route('admin.pendaftaran.terima', $baris->pendaftaran_id) }}" onsubmit="return confirm('Terima pelamar ini dan kurangi kuota lowongan?')">
                                        @csrf
                                        <button class="rounded-md bg-emerald-800 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-900" type="submit">Terima pelamar</button>
                                    </form>
                                @else
                                    <span class="text-slate-500">Tidak ada tindakan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-8 text-center text-slate-500" colspan="7">Belum ada pendaftaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $rekap->links() }}</div>
@endsection