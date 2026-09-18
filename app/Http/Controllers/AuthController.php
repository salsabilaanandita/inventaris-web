<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

       if(Auth::attempt($request->only('email','password'))){
        $request->session()->regenerate();
        return redirect()->intended('/dashboard'); // nanti mengarah ke dashboard
    }

        return back()->withErrors(['email'=>'Email atau password salah'])->withInput();
    }

    public function logout(Request $request)
    {   
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff_gudang', // Default role for new registrations
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Berhasil mendaftar! Selamat datang.');
    }
}