<?php

namespace Yourify\Http\Controllers;

use Yourify\Models\Profile;

class ProfilesController extends Controller
{
    public function show(){
        return view('profile');
    }

    public function showOther($id = null){
        return view('profile.user',[
            'user' => Profile::findOrFail($id)
        ]);
    }

    public function onListUsers(){
        return view('list', [
            'user' => Profile::all(),
        ]);
    }
}
