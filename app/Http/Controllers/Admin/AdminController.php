<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function profile()
    {
        return view('admin.profile');
    }
    public function Updateprofile(Request $request)
    {
        // validate
        $this->validate($request, [
            'name' => 'required|string|min:4',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|confirmed|min:6'
        ]);

        // update
        Auth::guard('admin')->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        // send message
        session()->flash('msg', 'updated succfully');

        // go back
        return back();
    }
}
