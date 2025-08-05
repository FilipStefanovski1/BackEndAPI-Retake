<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Debug logging
        $user = User::where('email', $request->email)->first();

        Log::info('Login attempt', [
            'email' => $request->email,
            'user_found' => $user ? true : false,
            'stored_hash' => $user?->password,
            'password_match' => $user ? Hash::check($request->password, $user->password) : null,
            'user_role' => $user?->role,
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Log::info('Login successful', [
                'email' => $request->email,
                'role' => Auth::user()->role,
            ]);

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/profile');
        }

        Log::warning('Login failed: credentials did not match', [
            'email' => $request->email
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
