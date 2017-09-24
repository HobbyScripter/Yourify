<?php

namespace Yourify\Http\Controllers;


use Yourify\Models\News;
use Yourify\Models\User;
use Yourify\Models\Version;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view('home', [
            'news' => News::all(),
            'users' => $users,
            //'show_version' => Version::all()
        ]);
    }

    public function profile(){
        return view('profile');
    }

}
