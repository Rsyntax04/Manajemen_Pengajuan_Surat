{{-- resources/views/admin/users/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Create User')

@section('page-title', 'Create User')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- HEADER --}}
        <div class="p-6 border-b">

            <h2 class="text-2xl font-bold text-gray-800">
                Tambah User
            </h2>

            <p class="text-gray-500 mt-1">
                Tambahkan user baru ke dalam sistem
            </p>

        </div>

        {{-- FORM --}}
        <form action="{{ route('users.store') }}"
              method="POST"
              class="p-6">

            @csrf

            {{-- NAME --}}
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('name')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- NAME --}}
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    NRP/NIK
                </label>

                <input type="text"
                       name="identitas"

                       class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('identitas')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- EMAIL --}}
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('email')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- PASSWORD --}}
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Password
                </label>

                <input type="password"
                       name="password"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('password')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- ROLE --}}
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Role
                </label>

                <select name="role_id"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">
                        -- Pilih Role --
                    </option>

                    @foreach($roles as $role)

                        <option value="{{ $role->id }}"
                            {{ old('role_id') == $role->id ? 'selected' : '' }}>

                            {{ $role->role_name }}

                        </option>

                    @endforeach

                </select>

                @error('role_id')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- STATUS AKTIF --}}
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status Akun
                </label>

                <select name="is_active"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif (Suspend)</option>
                </select>

                @error('is_active')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('users.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-xl transition">

                    Kembali
                </a>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition">

                    Simpan User
                </button>

            </div>

        </form>

    </div>

</div>

@endsection