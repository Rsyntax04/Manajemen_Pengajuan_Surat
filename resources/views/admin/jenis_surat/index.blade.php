@extends('layouts.app')

@section('title', 'Jenis Surat')

@section('page-title', 'Jenis Surat')


@section('content')

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">


        {{-- HEADER --}}
        <div class="p-6 border-b flex justify-between items-center">


            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Jenis Surat
                </h2>

                <p class="text-gray-500 mt-1">
                    Manage template surat dan field pengajuan
                </p>

            </div>



            <a href="{{ route('jenis-surat.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

                + Tambah Surat

            </a>


        </div>



        @if (session('success'))
            <div class="m-6 bg-green-100 text-green-700 p-3 rounded-xl">

                {{ session('success') }}

            </div>
        @endif




        <div class="overflow-x-auto">


            <table class="w-full">


                <thead class="bg-gray-100">


                    <tr>

                        <th class="px-6 py-4 text-left">
                            No
                        </th>


                        <th class="px-6 py-4 text-left">
                            Nama Surat
                        </th>


                        <th class="px-6 py-4 text-left">
                            Kode
                        </th>




                        <th class="px-6 py-4 text-left">
                            Field
                        </th>


                        <th class="px-6 py-4 text-center">
                            Action
                        </th>


                    </tr>


                </thead>



                <tbody>



                    @forelse($jenisSurat as $item)
                        <tr class="border-b hover:bg-gray-50">


                            <td class="px-6 py-4">

                                {{ $loop->iteration }}

                            </td>



                            <td class="px-6 py-4">


                                <div class="font-semibold">

                                    {{ $item->nama_jenis }}

                                </div>


                            </td>



                            <td class="px-6 py-4">


                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">

                                    {{ $item->kode_surat }}

                                </span>


                            </td>




                            




                            <td class="px-6 py-4">


                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

                                    {{ $item->fields->count() }} Field

                                </span>


                            </td>





                            <td class="px-6 py-4">


                                <div class="flex justify-center gap-2">



                                    <a href="{{ route('jenis-surat.edit', $item->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">

                                        Edit

                                    </a>





                                    <form action="{{ route('jenis-surat.delete', $item->id) }}" method="POST">


                                        @csrf

                                        @method('DELETE')


                                        <button onclick="return confirm('Hapus data?')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">


                                            Delete


                                        </button>


                                    </form>


                                </div>


                            </td>



                        </tr>



                    @empty


                        <tr>

                            <td colspan="6" class="text-center py-10 text-gray-500">


                                Belum ada data


                            </td>

                        </tr>
                    @endforelse



                </tbody>



            </table>

            <div class="p-6">

                {{ $jenisSurat->links() }}

            </div>

        </div>


    </div>


@endsection
