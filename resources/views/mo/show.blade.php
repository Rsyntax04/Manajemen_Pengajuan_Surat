@extends('layouts.mo')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan Surat')

@section('content')

<div class="mb-6">
    <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-4">{{ $pengajuan->jenisSurat->nama_jenis }}</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">Pemohon</p>
            <p class="font-semibold">{{ $pengajuan->user->name ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Status</p>
            <p class="font-semibold">
                @if($pengajuan->status == 'pending')
                    <span class="text-yellow-600">Pending</span>
                @elseif($pengajuan->status == 'approved')
                    <span class="text-green-600">Approved</span>
                @elseif($pengajuan->status == 'rejected')
                    <span class="text-red-600">Rejected</span>
                @elseif($pengajuan->status == 'revisi')
                    <span class="text-orange-600">Revisi</span>
                @else
                    <span class="text-gray-600">{{ $pengajuan->status }}</span>
                @endif
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
            <p class="font-semibold">{{ $pengajuan->created_at->format('d M Y H:i') }}</p>
        </div>
        @if($pengajuan->catatan_revisi)
        <div>
            <p class="text-sm text-gray-500">Catatan Revisi / Penolakan</p>
            <p class="font-semibold text-red-600">{{ $pengajuan->catatan_revisi }}</p>
        </div>
        @endif
    </div>

    @if($pengajuan->details && $pengajuan->details->count() > 0)
    <h3 class="text-xl font-bold mb-3 border-b pb-2">Detail Data</h3>
    <table class="w-full text-left border-collapse mb-6">
        <tbody>
            @foreach($pengajuan->details as $detail)
            <tr class="border-b">
                <th class="py-2 pr-4 text-gray-600 w-1/3">{{ ucwords(str_replace('_', ' ', $detail->field_name)) }}</th>
                <td class="py-2">{{ $detail->field_value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($pengajuan->anggota && $pengajuan->anggota->count() > 0)
    <h3 class="text-xl font-bold mb-3 border-b pb-2">Daftar Anggota</h3>
    <div class="overflow-x-auto mb-6">
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">NIM / NRP</th>
                    <th class="border p-2">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->anggota as $index => $anggota)
                <tr>
                    <td class="border p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $anggota->nama }}</td>
                    <td class="border p-2">{{ $anggota->identitas }}</td>
                    <td class="border p-2">{{ $anggota->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($pengajuan->panitia && $pengajuan->panitia->count() > 0)
    <h3 class="text-xl font-bold mb-3 border-b pb-2">Daftar Kepanitiaan</h3>
    <div class="overflow-x-auto mb-6">
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">NRP / NIK</th>
                    <th class="border p-2">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->panitia as $index => $panitia)
                <tr>
                    <td class="border p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $panitia->nama }}</td>
                    <td class="border p-2">{{ $panitia->identitas }}</td>
                    <td class="border p-2">{{ $panitia->jabatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
