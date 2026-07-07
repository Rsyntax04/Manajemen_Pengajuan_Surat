<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @vite('resources/css/app.css', 'resources/js/app.js')
</head>
<body class="bg-gray-100 min-h-screen">

    @if(session('success'))
        <div id="toast-success" class="fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg transition-all duration-500">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="fixed top-5 right-5 z-50 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg transition-all duration-500">
            {{ session('error') }}
        </div>
    @endif


    <div class="flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-slate-900 text-white min-h-screen shadow-lg">
            <div class="p-6 border-b border-slate-700">
                <h1 class="text-2xl font-bold">{{ Auth::user()->role_name }} Panel</h1>
            </div>

            <nav class="p-4 space-y-2">

                <a href="{{ route('mo.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 transition duration-200">
                    Dashboard
                </a>
                <a href="{{ route('mo.approval') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 transition duration-200">
                    Approval
                </a>

                <a href="{{ route('mo.history') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 transition duration-200">
                    History
                </a>

                <a href="{{ route('mo.template.builder') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 transition duration-200">
                    Template Builder
                </a>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1">

            {{-- Top Navbar --}}
            <header class="bg-white shadow-md">
                <div class="flex justify-between items-center px-8 py-4">

                    <h2 class="text-2xl font-semibold text-gray-700">
                        @yield('page-title')
                    </h2>

                    <div class="flex items-center gap-4">

                        <span class="text-gray-600 font-medium">
                            {{ Auth::user()->name }}
                        </span>

                        <form action="/logout" method="POST">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                                Logout
                            </button>
                        </form>

                    </div>

                </div>
            </header>

            {{-- Content --}}
            <main class="p-8">
                @yield('content')
            </main>

        </div>

    </div>

    @yield('script')

    <script>
        setTimeout(() => {
            const successToast = document.getElementById('toast-success');
            if (successToast) {
                successToast.style.opacity = '0';
                setTimeout(() => successToast.remove(), 500);
            }
            const errorToast = document.getElementById('toast-error');
            if (errorToast) {
                errorToast.style.opacity = '0';
                setTimeout(() => errorToast.remove(), 500);
            }
        }, 3000);
    </script>
</body>

</html>
