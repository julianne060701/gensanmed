<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
<<<<<<< HEAD
     *  @return string
=======
     * @return string
>>>>>>> 7f9afcd3d3b5969cc60d7c89e3766e0e8c4ffa42
     */
    protected function redirectTo()
    {
        $role = auth()->user()->role; // Assuming you have a 'role' field in your users table

        if ($role === 'IT') {
            return '/IT/home'; // Adjust the URL/path if necessary
        } elseif ($role === 'admin') {
            return '/admin/home';
        }

        // Default redirection if no role matches
        return '/';
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
