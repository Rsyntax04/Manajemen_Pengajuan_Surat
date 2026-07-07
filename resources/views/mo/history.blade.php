@extends('layouts.mo')

@section('title','History Pengajuan')

@section('content')

<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    {{-- HEADER --}}
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">
            History Pengajuan Surat
        </h2>

        <p class="text-gray-500 mt-1">
            Monitoring seluruh pengajuan surat pengguna
        </p>
    </div>


    {{-- FILTER & SEARCH --}}
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" action="{{ route('mo.history') }}" class="space-y-4">
            <div class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Filter Jenis Surat</label>
                    <select name="jenis_surat_id" class="border border-gray-300 rounded-xl px-4 py-2 w-48" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($jenisSurats as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="border border-gray-300 rounded-xl px-4 py-2">
                </div>
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="border border-gray-300 rounded-xl px-4 py-2">
                </div>
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Cari Nama</label>
                    <input type="text" name="search" placeholder="Nama Mahasiswa/Dosen" value="{{ request('search') }}"
                        class="border border-gray-300 rounded-xl px-4 py-2 w-64">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl mb-[2px]">Cari & Filter</button>
                <a href="{{ route('mo.history') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-xl flex items-center mb-[2px]">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
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

            @forelse($pengajuan as $item)

                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="px-6 py-4">
                        {{ $loop->iteration }}
                    </td>


                    <td class="px-6 py-4">

                        <div class="font-semibold text-gray-800">
                            {{ $item->user->name ?? '-' }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $item->user->email ?? '' }}
                        </div>

                    </td>


                    <td class="px-6 py-4">

                        <div class="font-semibold text-gray-800">
                            {{ $item->jenisSurat->nama_jenis ?? '-' }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $item->jenisSurat->kode_surat ?? '' }}
                        </div>

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

                        @else

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                {{ $item->status }}
                            </span>

                        @endif

                    </td>


                    <td class="px-6 py-4 text-gray-600">
                        {{ $item->created_at->format('d M Y') }}
                    </td>


                    <td class="px-6 py-4 text-center">

                        <a
                            href="{{ route('mo.pengajuan.show', $item->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            Detail
                        </a>

                    </td>

                </tr>


            @empty

                <tr>

                    <td colspan="6"
                        class="text-center py-10 text-gray-500">

                        Belum ada pengajuan surat

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