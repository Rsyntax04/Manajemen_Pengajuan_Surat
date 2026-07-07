<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\ResetPasswordMail;
class AuthController extends Controller
{
    // tampil halaman login
    public function login()
    {
        return view('auth.login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }



    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);


        $user = User::where(
            'email',
            $request->email
        )->first();



        if(!$user){

            return back()
            ->with('error','Email tidak ditemukan');

        }



        $code = rand(100000,999999);



        DB::table('password_reset_tokens')
        ->updateOrInsert(

            [
                'email'=>$user->email
            ],

            [
                'token'=>$code,
                'created_at'=>now()
            ]

        );



        Mail::to($user->email)
        ->send(
            new ResetPasswordMail($code)
        );



        return redirect()
        ->route(
            'password.verify',
            $user->id
        );

    }

    public function verifyCode($id)
    {
        $user = User::findOrFail($id);


        return view(
            'auth.verify-code',
            compact('user')
        );
    }

    public function checkCode(Request $request,$id)
    {

        $request->validate([
            'code'=>'required'
        ]);



        $user = User::findOrFail($id);



        $token = DB::table('password_reset_tokens')
        ->where('email',$user->email)
        ->first();




        if(!$token){

            return back()
            ->with('error','Code tidak ditemukan');

        }




        if($token->token != $request->code){

            return back()
            ->with('error','Code salah');

        }





        if(
            Carbon::parse($token->created_at)
            ->addMinutes(10)
            ->isPast()
        ){

            return back()
            ->with('error','Code expired');

        }



        // simpan status OTP berhasil
        session([
            'reset_verified'=>$user->id
        ]);



        return redirect()
        ->route(
            'password.reset',
            $user->id
        );


    }

    public function resetPassword($id)
    {

        // cegah bypass URL
        if(
            session('reset_verified') != $id
        ){

            return redirect('/')
            ->with('error','Silahkan verifikasi kode terlebih dahulu');

        }



        $user = User::findOrFail($id);



        return view(
            'auth.reset-password',
            compact('user')
        );

    }

    public function updatePassword(Request $request,$id)
    {

        if(
            session('reset_verified') != $id
        ){

            return redirect('/')
            ->with('error','Unauthorized');

        }



        $request->validate([

            'password' => [
                'required',
                'min:8',
                'confirmed'
            ]

        ],
        [

            'password.required'
                => 'Password wajib diisi',

            'password.min'
                => 'Password minimal 8 karakter',

            'password.confirmed'
                => 'Password dan konfirmasi password tidak sama'

        ]);



        $user = User::findOrFail($id);



        $user->password =
        Hash::make($request->password);



        $user->save();



        DB::table('password_reset_tokens')
        ->where('email',$user->email)
        ->delete();



        session()->forget('reset_verified');



        return redirect('/')
        ->with(
            'success',
            'Password berhasil diubah'
        );

    }

    // proses login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
            }

            $request->session()->regenerate();

            $role = Auth::user()->role_id;

            switch ($role) {

                case 1:
                    return redirect('/admin/dashboard');
                case 2:
                    return redirect('/mo/dashboard');
                default:
                    return redirect('/user/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|confirmed'
        ]);


        User::create([
            'name'=>$request->name,
            'identitas'=>$request->identitas,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role_id'=>4
        ]);


        return redirect('/')
            ->with('success','Register berhasil, silahkan login');
    }
    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}