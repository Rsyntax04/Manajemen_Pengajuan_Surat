@extends('layouts.app')

@section('title', 'Activity Logs')

@section('page-title', 'Activity Logs')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- HEADER --}}
        <div class="p-6 border-b flex justify-between items-center gap-6">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Activity Logs
                </h2>

                <p class="text-gray-500 mt-1">
                    Riwayat aktivitas admin dan user dalam sistem
                </p>

            </div>
            {{-- SEARCH --}}
            <div class="w-full max-w-md">

                <form method="GET">

                    <div class="flex gap-3">

                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari activity, description, atau user..."
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-xl transition">

                            Search

                        </button>

                    </div>

                </form>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-left text-gray-600 text-sm">

                        <th class="px-6 py-4 font-semibold">#</th>
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
                            {{ $activities->firstItem() + $index }}
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

        {{-- PAGINATION --}}
        <div class="p-6 border-t bg-gray-50">

            <div class="flex justify-end">
                {{ $activities->links() }}
            </div>

        </div>

    </div>

</div>

@endsection