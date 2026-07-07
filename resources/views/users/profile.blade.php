@extends('layouts.user')

@section('title', 'Profile')

@section('page-title', 'Profile')

@section('content')

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        <div class="p-6 border-b flex justify-between items-center">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    User Profile
                </h2>

                <p class="text-gray-500 mt-1">
                    Informasi akun pengguna
                </p>

            </div>


            <a href="{{ route('user.profile.edit') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition">

                Edit Profile

            </a>

        </div>

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>

                    <label class="block text-gray-500 text-sm mb-1">
                        Name
                    </label>

                    <div class="bg-gray-50 rounded-xl px-4 py-3 font-semibold text-gray-800">
                        {{ $user->name }}
                    </div>
                </div>


                <div>

                    <label class="block text-gray-500 text-sm mb-1">
                        Email
                    </label>

                    <div class="bg-gray-50 rounded-xl px-4 py-3 font-semibold text-gray-800">
                        {{ $user->email }}
                    </div>

                </div>


                <div>

                    <label class="block text-gray-500 text-sm mb-1">
                        Identitas
                    </label>

                    <div class="bg-gray-50 rounded-xl px-4 py-3 font-semibold text-gray-800">
                        {{ $user->identitas ?? '-' }}
                    </div>

                </div>


                <div>

                    <label class="block text-gray-500 text-sm mb-1">
                        Phone
                    </label>

                    <div class="bg-gray-50 rounded-xl px-4 py-3 font-semibold text-gray-800">
                        {{ $user->phone ?? '-' }}
                    </div>

                </div>


                <div>

                    <label class="block text-gray-500 text-sm mb-1">
                        Role
                    </label>

                    <div class="bg-blue-100 text-blue-700 rounded-xl px-4 py-3 font-semibold">
                        {{ $user->role->name ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
