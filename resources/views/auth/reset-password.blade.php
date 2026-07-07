<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-slate-900 to-slate-700 min-h-screen flex items-center justify-center">

<div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">

    <div class="text-center mb-8">

        <h1 class="text-3xl font-bold text-slate-800">
            Reset Password
        </h1>

        <p class="text-gray-500 mt-2">
            Create new password
        </p>

    </div>


    @if(session('error'))

        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5">
            {{ session('error') }}
        </div>

    @endif


    <form action="{{ route('password.update', $user->id) }}" method="POST" class="space-y-5">

        @csrf

        <div>

            <label class="block text-gray-700 font-medium mb-2">
                New Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Enter new password"
                class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700 @error('password') border-red-500 @enderror">


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
                class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700 @error('password') border-red-500 @enderror">


            @error('password')

                <p class="text-red-600 text-sm mt-2">
                    {{ $message }}
                </p>

            @enderror

        </div>


        <button
            type="submit"
            class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-semibold transition">

            Update Password

        </button>

    </form>

</div>

</body>

</html>