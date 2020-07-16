<?php

namespace App\Http\Controllers\Api\oauth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:' . User::PASSMIN . "|max:" . User::PASSMAX  . "|confirmed",
            'phone' => 'required|string|min:10|max:15'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone
        ]);

        $user->sendApiEmailVerificationNotification();

        return responder()->success()->meta(['code' => 'created', 'message' => 'created succfully', 'verify' => 'you must verify your email']);
    }
}
