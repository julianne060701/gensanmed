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
            return redirect()->route('admin/home');
        } elseif ($user->hasRole('engineer')) {
            return redirect()->route('engineer.index');
        } elseif ($user->hasRole('IT')) {
            return redirect()->route('IT.index');
        }
    
        return redirect('/')->withErrors(['message' => 'You are not authorized.']);
    }

    /**
     * Handle post-authentication redirection.
     */
    protected function authenticated(Request $request, $user)
    {
        return $this->redirectUser($user);
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
