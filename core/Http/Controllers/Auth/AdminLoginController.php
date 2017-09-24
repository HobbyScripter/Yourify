<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 14.03.2017
 * Time: 20:05
 */

namespace Yourify\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth;
use Yourify\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function showLoginForm(){
        return view('auth.admin-login');
    }

    public function login(Request $request){
        $this->validate($request, [
           'name' => 'required',
            'password' => 'required|min:6'
        ]);


        if(Auth::guard('admin')->attempt(['username' => $request->name, 'password' => $request->password],$request->remember)){
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()->withInput($request->only('name','remember'));
    }

}