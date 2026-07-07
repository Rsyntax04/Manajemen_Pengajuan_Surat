@extends('layouts.mo')

@section('title', 'Dashboard MO')

@section('page-title', 'Dashboard')

@section('content')

<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    {{-- HEADER --}}
    <div class="p-6 border-b">

        <h2 class="text-2xl font-bold text-gray-800">
            Dashboard MO
        </h2>

        <p class="text-gray-500 mt-1">
            Monitoring pengajuan surat terbaru
        </p>

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

                </tr>

            </thead>


            <tbody>

            @forelse($latest as $index => $item)

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

                    </td>


                    {{-- SURAT --}}
                    <td class="px-6 py-4">

                        {{ $item->jenisSurat->nama_jenis ?? '-' }}

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


                </tr>


            @empty

                <tr>

                    <td colspan="5"
                        class="text-center py-10 text-gray-500">

                        Tidak ada data pengajuan

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection