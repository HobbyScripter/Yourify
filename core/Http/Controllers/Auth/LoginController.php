<?php

namespace Yourify\Http\Controllers\Auth;

use Yourify\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
	
	public function username()
	{
		return 'username';
	}
	protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 
            'password' => 'required',
            // new rules here
        ]);
    }
	protected function credentials(Request $request)
	{
		$usernameInput = trim($request->{$this->username()});
		$usernameColumn = filter_var($usernameInput, FILTER_VALIDATE_EMAIL) ? 'email' : $this->username();

		return [$usernameColumn => $usernameInput, 'password' => $request->password];
	}
}
