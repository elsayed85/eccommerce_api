<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // user data
    public function user()
    {
        return request()->user();
    }

    // update email , name , password of user
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'email' => 'sometimes|email|unique:users,email',
            'password' => 'sometimes|min:' . User::PASSMIN . "|max:" . User::PASSMAX,
            'phone' => 'sometimes|min:7|max:15|unique:users,phone'
        ]);
        $updated = [];
        if ($request->has('email')) {
            Auth::user()->update([
                'email' => $request->email,
                'email_verified_at' => null
            ]);
            $updated['email'] = 'email updated';
            $updated['email-verifcation'] = 'email must be verified';
        }
        if ($request->has('phone')) {
            Auth::user()->update([
                'phone' => $request->phone,
            ]);
            $updated['phone'] = 'phone updated';
        }
        if ($request->has('password')) {
            Auth::user()->update([
                'password' => Hash::make($request->password)
            ]);
            $updated['password'] = 'password updated';
        }
        if (count($updated) == 0) {
            return responder()->error('nothing',  'update somthing first');
        }
        return responder()->success(null)->meta($updated);
    }

    // delete account
    public function softDelete()
    {
        $user = request()->user();

        $this->tokens()->each->delete();

        $user->delete();

        return responder()->success()->meta(['delete' => 'deleted sucffully'])->respond();
    }

    public function tokens()
    {
        return request()->user()->tokens;
    }
}
