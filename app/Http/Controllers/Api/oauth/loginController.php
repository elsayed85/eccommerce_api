<?php

namespace App\Http\Controllers\Api\oauth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email,deleted_at,NULL',
            'password' => 'required|min:' . User::PASSMIN . "|max:" . User::PASSMAX
        ]);

        $email = $request->email;
        $password = $request->password;

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return responder()->error('incorrect-data', 'email or password incorrect');
        }

        $user = Auth::user();

        if ($user->email_verified_at === NULL) {
            return responder()->error('verify-email', 'you must verify your email');
        }

        $token = $user->createToken('user');
        return responder()->success($user)->meta(['token' => $token->plainTextToken])->respond();
    }
}
