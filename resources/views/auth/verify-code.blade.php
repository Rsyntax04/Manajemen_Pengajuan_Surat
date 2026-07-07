<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Code</title>

    @vite('resources/css/app.css')

</head>


<body class="bg-gradient-to-br from-slate-900 to-slate-700 min-h-screen flex items-center justify-center">


    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">


        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-slate-800">

                Verification Code

            </h1>


            <p class="text-gray-500 mt-2">

                Enter the code sent to your email

            </p>


        </div>



        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5">

                {{ session('error') }}

            </div>
        @endif




        <form method="POST" action="{{ route('password.check', $user->id) }}" class="space-y-5">


            @csrf



            <div>


                <label class="block text-gray-700 font-medium mb-2">

                    Verification Code

                </label>



                <input type="text" name="code" maxlength="6" placeholder="Enter 6 digit code"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-700 text-center tracking-widest text-lg"
                    required>


            </div>





            <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-semibold transition duration-200">


                Verify Code


            </button>




        </form>



    </div>


</body>

</html>
