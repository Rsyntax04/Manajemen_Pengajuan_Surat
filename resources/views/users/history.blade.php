@extends('layouts.user')

@section('title','History Pengajuan')

@section('content')

<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    @if(session('success'))

        <div class="mb-5 bg-green-100 text-green-700 px-5 py-3 rounded-xl">

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="mb-5 bg-red-100 text-red-700 px-5 py-3 rounded-xl">

            {{ session('error') }}

        </div>

    @endif
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">History Pengajuan</h2>
        <p class="text-gray-500 mt-1">Daftar pengajuan surat yang telah dibuat</p>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" action="{{ route('user.history') }}" class="space-y-4">
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
                    <label class="text-sm text-gray-600 block mb-1">Cari Jenis Surat</label>
                    <input type="text" name="search" placeholder="Pencarian..." value="{{ request('search') }}"
                        class="border border-gray-300 rounded-xl px-4 py-2 w-64">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl mb-[2px]">Cari</button>
                <a href="{{ route('user.history') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-xl mb-[2px]">Reset</a>
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
                        Jenis Surat
                    </th>

                    <th class="px-6 py-4 text-left text-gray-600">
                        Tanggal
                    </th>

                    <th class="px-6 py-4 text-left text-gray-600">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-gray-600">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

            @forelse($pengajuan as $item)

                <tr class="border-b hover:bg-gray-50 transition">


                    <td class="px-6 py-4">
                        {{ $loop->iteration }}
                    </td>


                    <td class="px-6 py-4">

                        <div class="font-semibold text-gray-800">

                            {{ $item->jenisSurat->nama_jenis ?? '-' }}

                        </div>

                    </td>


                    <td class="px-6 py-4 text-gray-600">

                        {{ $item->created_at->format('d M Y') }}

                    </td>


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

                        @elseif($item->status == 'revisi')

                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                                Revisi
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                {{ $item->status }}
                            </span>

                        @endif
                        
                        @if($item->catatan_revisi)
                            <div class="mt-2 text-xs text-red-500 italic">
                                Catatan: {{ $item->catatan_revisi }}
                            </div>
                        @endif

                    </td>


                    <td class="px-6 py-4 text-center">

                        @if($item->status == 'approved')
                            <a href="{{ route('user.pengajuan.download',$item->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition inline-block">
                                Download
                            </a>
                        @elseif($item->status == 'pending' || $item->status == 'revisi')
                            <a href="{{ route('user.pengajuan.edit',$item->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition inline-block">
                                Edit
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">
                                Belum tersedia
                            </span>
                        @endif

                    </td>


                </tr>


            @empty

                <tr>

                    <td colspan="5"
                        class="text-center py-10 text-gray-500">

                        Belum ada pengajuan surat

                    </td>

                </tr>

            @endforelse


            </tbody>

        </table>

    </div>


    <div class="p-6">

        {{ $pengajuan->links() }}

    </div>

</div>

@endsection