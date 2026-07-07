<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    @vite('resources/css/app.css')

</head>


<body class="bg-gradient-to-br from-slate-900 to-slate-700 min-h-screen flex items-center justify-center">


    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">


        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-slate-800">

                Forgot Password

            </h1>


            <p class="text-gray-500 mt-2">

                Enter your email to reset password

            </p>


        </div>



        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">

                {{ session('error') }}

            </div>
        @endif





        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">


            @csrf



            <div>

                <label class="block text-gray-700 font-medium mb-2">

                    Email

                </label>


                <input type="email" name="email" placeholder="Enter your email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700"
                    required>


            </div>





            <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-semibold">


                Reset Password


            </button>




            <div class="text-center">


                <a href="{{ route('login') }}" class="text-sm text-slate-700 hover:underline">


                    Back to Login


                </a>


            </div>



        </form>



    </div>


</body>

</html>
