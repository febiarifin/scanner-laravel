<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Login',
        ];
        return view('auth.login', $data);
    }

    public function auth(Request $request)
    {
        $credentials = [
            "email" => $request->email,
            "password" => $request->password,
        ];

        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('index');
        }
        toastr()->error('User tidak ditemukan');
        return back();
    }

}
