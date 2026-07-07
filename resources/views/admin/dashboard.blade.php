@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- Total Users -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">
            Total Users
        </h2>

        <p class="text-4xl font-bold mt-2">
            {{ $totalUsers }}
        </p>
    </div>

    <!-- Total Admin -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">
            Total Admin
        </h2>

        <p class="text-4xl font-bold mt-2">
            {{ $totalAdmin }}
        </p>
    </div>

    <!-- Total Dosen -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">
            Total Dosen
        </h2>

        <p class="text-4xl font-bold mt-2">
            {{ $totalDosen }}
        </p>
    </div>

    <!-- Total Mahasiswa -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">
            Total Mahasiswa
        </h2>

        <p class="text-4xl font-bold mt-2">
            {{ $totalMahasiswa }}
        </p>
    </div>

</div>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden mt-8">

        {{-- HEADER --}}
        <div class="p-6 border-b flex justify-between items-center">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Recent Activities
                </h2>
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-left text-gray-600 text-sm">

                        <th class="px-6 py-4 font-semibold">No</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Activity</th>
                        <th class="px-6 py-4 font-semibold">Description</th>
                        <th class="px-6 py-4 font-semibold">Date</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($activities as $index => $activity)

                    <tr class="hover:bg-gray-50 transition">

                        {{-- NO --}}
                        <td class="px-6 py-4 text-gray-600">
                            {{ $index + 1 }}
                        </td>

                        {{-- USER --}}
                        <td class="px-6 py-4">

                            <div class="font-semibold text-gray-800">
                                {{ $activity->user?->name ?? 'System' }}
                            </div>

                        </td>

                        {{-- ACTIVITY --}}
                        <td class="px-6 py-4">

                            @php
                                $badge = match($activity->activity) {
                                    'Login' => 'bg-green-100 text-green-700',
                                    'Logout' => 'bg-gray-200 text-gray-700',
                                    'Create User' => 'bg-blue-100 text-blue-700',
                                    'Update User' => 'bg-yellow-100 text-yellow-700',
                                    'Delete User' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                {{ $activity->activity }}
                            </span>

                        </td>

                        {{-- DESCRIPTION --}}
                        <td class="px-6 py-4 text-gray-600">
                            {{ $activity->description }}
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 text-gray-500">

                            <div class="text-sm">
                                {{ $activity->created_at->format('d M Y') }}
                            </div>

                            <div class="text-xs text-gray-400">
                                {{ $activity->created_at->format('H:i') }}
                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center py-10 text-gray-500">

                            <div class="flex flex-col items-center gap-2">

                                <span class="text-3xl">📭</span>
                                <span>Belum ada activity logs</span>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>
@endsection