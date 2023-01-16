<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginActionController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credential = $request->only(['username', 'password']);
        if (Auth::attempt($credential)) {
           return redirect(url('cr-table'));
        } 
        
        return redirect(url('login'))->with('status', 'username atau password anda salah');
    }
}
