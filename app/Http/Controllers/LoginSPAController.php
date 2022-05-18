<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginSPAController extends Controller {
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Invalid Credentials',
        ])->onlyInput('email');
    }

    public function register(Request $request) {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $creds['email'])->first();
        if (!$user) {
            User::create([
                'name' => $request->name,
                'email' => $creds['email'],
                'password' => Hash::make($creds['password'])
            ]);
        }

        return $this->login($request);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    function loginForm() {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('login');
    }
}
