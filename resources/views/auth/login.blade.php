<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-slate-900 to-slate-700 min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                Welcome
            </h1>

            <p class="text-gray-500 mt-2">
                Login to your account
            </p>
        </div>

        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Email
                </label>

                <input type="email" name="email" placeholder="Enter your email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Password
                </label>

                <input type="password" name="password" placeholder="Enter your password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700">
            </div>

            @error('email')
                <p class="text-red-600 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror

            {{-- Remember --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}" class="text-sm text-slate-700 hover:underline">
                    Forgot password?
                </a>

            </div>
            <div class="flex items-center justify-between">
                <a href="{{ route('register') }}" class="text-sm text-slate-700 hover:underline">
                    Register
                </a>
            </div>

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-semibold transition duration-200">
                Login
            </button>

        </form>

    </div>

</body>

</html>
