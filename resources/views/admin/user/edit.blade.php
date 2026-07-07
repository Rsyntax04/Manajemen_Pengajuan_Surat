{{-- resources/views/admin/users/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- HEADER --}}
        <div class="p-6 border-b">

            <h2 class="text-2xl font-bold text-gray-800">
                Edit User
            </h2>

            <p class="text-gray-500 mt-1">
                Update data user yang dipilih
            </p>

        </div>

        {{-- FORM --}}
        <form action="{{ route('users.update', $user->id) }}"
              method="POST"
              class="p-6">

            @csrf
            @method('PUT')

            {{-- NAME (READONLY) --}}
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama
                </label>

                <input type="text"
                       value="{{ $user->name }}"
                       readonly
                       class="w-full bg-gray-100 border border-gray-300 rounded-xl p-3 cursor-not-allowed">

            </div>

            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    NRP / NIK
                </label>

                <input type="text"
                       value="{{ $user->identitas }}"
                       readonly
                       class="w-full bg-gray-100 border border-gray-300 rounded-xl p-3 cursor-not-allowed">

            </div>

            {{-- EMAIL --}}
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email
                </label>

                <input type="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-300 rounded-xl p-3 cursor-not-allowed"
                       readonly>
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

            {{-- PASSWORD
            <div class="mb-5">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Password (opsional)
                </label>

                <input type="password"
                       name="password"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500">

                <p class="text-sm text-gray-400 mt-1">
                    Kosongkan jika tidak ingin mengubah password
                </p>

            </div> --}}

            {{-- ROLE --}}
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Role
                </label>

                <select name="role_id"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500">

                    @foreach($roles as $role)

                        <option value="{{ $role->id }}"
                            {{ $user->role_id == $role->id ? 'selected' : '' }}>

                            {{ $role->role_name }}

                        </option>

                    @endforeach

                </select>

                @error('role_id')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

            {{-- STATUS AKTIF --}}
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status Akun
                </label>

                <select name="is_active"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif (Suspend)</option>
                </select>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('users.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-xl transition">

                    Cancel
                </a>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition">

                    Update User
                </button>

            </div>

        </form>

    </div>

</div>

@endsection