

@extends('layouts.app')

@section('title', 'User Management')

@section('page-title', 'User Management')

@section('content')
<div class="bg-white rounded-2xl shadow-md overflow-hidden">


    {{-- SEARCH BAR --}}
    <div class="p-6 border-b bg-gray-50">

        <form method="GET"
            action="{{ route('users.index') }}">

            <div class="flex gap-3">

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, email user, NRP/NIK..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-xl transition">

                    Search

                </button>

            </div>

        </form>

    </div>
    {{-- HEADER --}}
    <div class="p-6 border-b flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                User Management
            </h2>

            <p class="text-gray-500 mt-1">
                Manage all registered users
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition">

            + Tambah User
        </a>

    </div>
    

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        No
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Name
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Email
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Role
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Created At
                    </th>

                    <th class="text-center px-6 py-4 font-semibold text-gray-600">
                        Status
                    </th>

                    <th class="text-center px-6 py-4 font-semibold text-gray-600">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $index => $user)

                <tr class="border-b hover:bg-gray-50 transition">

                    {{-- NUMBER --}}
                    <td class="px-6 py-4">
                        {{ $index + 1 }}
                    </td>

                    {{-- NAME --}}
                    <td class="px-6 py-4">

                        <div class="font-semibold text-gray-800">
                            {{ $user->name }}
                        </div>

                    </td>

                    {{-- EMAIL --}}
                    <td class="px-6 py-4 text-gray-600">
                        {{ $user->email }}
                    </td>

                    {{-- ROLE --}}
                    <td class="px-6 py-4">

                        @if($user->role->role_name == 'admin')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Admin
                            </span>

                        @elseif($user->role->role_name == 'dosen')

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                Dosen
                            </span>

                        @elseif($user->role->role_name == 'mahasiswa')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Mahasiswa
                            </span>
                        @else
                            <span class="bg-purple-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                MO Staff
                            </span>
                        @endif

                    </td>

                    {{-- CREATED --}}
                    <td class="px-6 py-4 text-gray-600">
                        {{ $user->created_at->format('d M Y') }}
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4 text-center">
                        @if($user->is_active)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                Aktif
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-medium">
                                Nonaktif
                            </span>
                        @endif
                    </td>


                    <td class="px-6 py-4">

                        <div class="flex justify-center gap-2">

                            {{-- EDIT --}}
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">

                                Edit
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('users.destroy', $user->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Yakin ingin menghapus user ini?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">

                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        class="text-center py-10 text-gray-500">

                        Tidak ada data user
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection