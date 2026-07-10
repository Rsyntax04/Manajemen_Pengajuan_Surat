@extends('layouts.user')

@section('title','Edit Profile')

@section('page-title','Edit Profile')

@section('content')

<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    <div class="p-6 border-b">

        <h2 class="text-2xl font-bold text-gray-800">
            Edit Profile
        </h2>

        <p class="text-gray-500 mt-1">
            Update informasi akun
        </p>

    </div>


    <div class="p-6">

        <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-5">

            @csrf
            @method('PUT')


            <div>

                <label class="block text-gray-700 font-medium mb-2">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name',$user->name) }}"
                    class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">


                @error('name')

                <p class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>


            <div>

                <label class="block text-gray-700 font-medium mb-2">
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone',$user->phone) }}"
                    class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">
                @error('phone')
                <p class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>


            <div class="flex gap-3">


                <a href="{{ route('user.profile') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-xl">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

                    Save

                </button>


            </div>


        </form>

    </div>

</div>

@endsection