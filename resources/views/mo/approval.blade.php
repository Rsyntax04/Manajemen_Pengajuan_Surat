@extends('layouts.mo')


@section('title','Approval Pengajuan')


@section('page-title','Approval Pengajuan')


@section('content')


<div class="bg-white rounded-2xl shadow-md overflow-hidden">


    {{-- HEADER --}}
    <div class="p-6 border-b">


        <h2 class="text-2xl font-bold text-gray-800">

            Approval Pengajuan

        </h2>


        <p class="text-gray-500 mt-1">

            Kelola persetujuan pengajuan surat pengguna

        </p>


    </div>


    {{-- FILTER & SEARCH --}}
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" action="{{ route('mo.approval') }}" class="space-y-4">
            <div class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Filter Jenis Surat</label>
                    <select name="jenis_surat_id" class="border border-gray-300 rounded-xl px-4 py-2 w-64" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($jenisSurats as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Cari Nama</label>
                    <input type="text" name="search" placeholder="Nama Mahasiswa/Dosen" value="{{ request('search') }}"
                        class="border border-gray-300 rounded-xl px-4 py-2 w-64">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl mb-[2px]">Cari</button>
                <a href="{{ route('mo.approval') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-xl flex items-center mb-[2px]">Reset</a>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">


        <table class="w-full">


            <thead class="bg-gray-100">


                <tr>


                    <th class="px-6 py-4 text-left text-gray-600">
                        No
                    </th>


                    <th class="px-6 py-4 text-left text-gray-600">
                        User
                    </th>


                    <th class="px-6 py-4 text-left text-gray-600">
                        Surat
                    </th>


                    <th class="px-6 py-4 text-left text-gray-600">
                        Status
                    </th>


                    <th class="px-6 py-4 text-left text-gray-600">
                        Tanggal
                    </th>


                    <th class="px-6 py-4 text-center text-gray-600">
                        Action
                    </th>


                </tr>


            </thead>


            <tbody>


                @forelse($pengajuan as $index => $item)


                <tr class="border-b hover:bg-gray-50 transition">


                    {{-- NO --}}
                    <td class="px-6 py-4">


                        {{ $index + 1 }}


                    </td>


                    {{-- USER --}}
                    <td class="px-6 py-4">


                        <div class="font-semibold text-gray-800">


                            {{ $item->user->name ?? '-' }}


                        </div>


                        <div class="text-sm text-gray-500">


                            {{ $item->user->email ?? '' }}


                        </div>


                    </td>


                    {{-- SURAT --}}
                    <td class="px-6 py-4">


                        <div class="font-semibold text-gray-800">


                            {{ $item->jenisSurat->nama_jenis ?? '-' }}


                        </div>


                    </td>


                    {{-- STATUS --}}
                    <td class="px-6 py-4">


                        @if($item->status == 'pending')


                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">


                                Pending


                            </span>


                        @elseif($item->status == 'approved')


                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">


                                Approved


                            </span>


                        @elseif($item->status == 'rejected')


                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">


                                Rejected


                            </span>


                        @else


                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">


                                {{ $item->status }}


                            </span>


                        @endif


                    </td>


                    {{-- TANGGAL --}}
                    <td class="px-6 py-4 text-gray-600">


                        {{ $item->created_at->format('d M Y') }}


                    </td>


                    {{-- ACTION --}}
                    <td class="px-6 py-4">


                        <div class="flex justify-center gap-2">


                            {{-- DETAIL --}}
                            <a href="{{ route('mo.pengajuan.show', $item->id) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                                Detail
                            </a>

                            {{-- APPROVE --}}
                            <form

                            action="{{ route('mo.approve',$item->id) }}"

                            method="POST">


                                @csrf


                                <button

                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">


                                    Approve


                                </button>


                            </form>


                            {{-- REJECT --}}
                            <form action="{{ route('mo.reject',$item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="catatan_revisi" value="Ditolak MO" class="catatan-input">
                                <button type="button" onclick="promptCatatan(this)"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                    Reject
                                </button>
                            </form>

                            {{-- REVISI --}}
                            <form action="{{ route('mo.revisi',$item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="catatan_revisi" value="Harap perbaiki lampiran" class="catatan-input">
                                <button type="button" onclick="promptCatatan(this)"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                                    Revisi
                                </button>
                            </form>


                        </div>


                    </td>


                </tr>


                @empty


                <tr>


                    <td colspan="6"

                    class="text-center py-10 text-gray-500">


                        Tidak ada pengajuan terbaru


                    </td>


                </tr>


                @endforelse


            </tbody>


        </table>


    </div>


    {{-- PAGINATION --}}
    <div class="p-6">


        {{ $pengajuan->links() }}


    </div>


</div>


@endsection

@section('script')
<script>
function promptCatatan(button) {
    let catatan = prompt("Masukkan catatan (opsional):", button.previousElementSibling.value);
    if (catatan !== null) {
        button.previousElementSibling.value = catatan;
        button.closest('form').submit();
    }
}
</script>
@endsection