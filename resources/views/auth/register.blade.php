<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    @vite('resources/css/app.css')

</head>

<body class="bg-gradient-to-br from-slate-900 to-slate-700 min-h-screen flex items-center justify-center">

<div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">

    <div class="text-center mb-8">

        <h1 class="text-3xl font-bold text-slate-800">
            Create Account
        </h1>

        <p class="text-gray-500 mt-2">
            Register new account
        </p>

    </div>


    @if(session('error'))

        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5">
            {{ session('error') }}
        </div>

    @endif


    <form action="{{ route('register.process') }}" method="POST" class="space-y-5">

        @csrf


        <div>

            <label class="block text-gray-700 font-medium mb-2">
                Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter your name"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700 @error('name') border-red-500 @enderror">


            @error('name')

                <p class="text-red-600 text-sm mt-2">
                    {{ $message }}
                </p>

            @enderror

        </div>



        <div>

            <label class="block text-gray-700 font-medium mb-2">
                Identitas
            </label>

            <input
                type="text"
                name="identitas"
                value="{{ old('identitas') }}"
                placeholder="NIM / NIP"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700">


        </div>



        <div>

            <label class="block text-gray-700 font-medium mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700 @error('email') border-red-500 @enderror">


            @error('email')

                <p class="text-red-600 text-sm mt-2">
                    {{ $message }}
                </p>

            @enderror

        </div>



        <div>

            <label class="block text-gray-700 font-medium mb-2">
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Enter password"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700 @error('password') border-red-500 @enderror">


            @error('password')

                <p class="text-red-600 text-sm mt-2">
                    {{ $message }}
                </p>

            @enderror

        </div>



        <div>

            <label class="block text-gray-700 font-medium mb-2">
                Confirm Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirm password"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700">

        </div>



        <button
            type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-semibold transition">

            Register

        </button>


        <p class="text-center text-sm text-gray-500">

            Already have account?

            <a href="{{ route('login') }}" class="text-slate-700 font-semibold hover:underline">
                Login
            </a>

        </p>


    </form>

</div>

</body>

</html>