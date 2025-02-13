<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Redirect users after successful login.
     */
    protected function redirectUser($user)
    {
        if ($user->hasRole('administrator')) {
            return redirect()->route('admin.home'); // Fix route naming
        } elseif ($user->hasRole('engineer')) {
            return redirect()->route('engineer.home');
        } elseif ($user->hasRole('IT')) {
            return redirect()->route('IT.index');
        }elseif ($user->hasRole('purchaser')) {
            return redirect()->route('purchaser.home');
        }
    
        return redirect('/')->withErrors(['message' => 'You are not authorized.']);
    }
    

    /**
     * Handle post-authentication redirection.
     */
    protected function redirectTo()
{
    if (Auth::check()) {
        return $this->redirectUser(Auth::user());
    }
    return '/'; // Fallback to home if not authenticated
}
    

    /**
     * Logout user and redirect to login page.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
