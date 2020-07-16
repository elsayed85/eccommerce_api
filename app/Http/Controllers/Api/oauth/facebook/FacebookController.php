<?php

namespace App\Http\Controllers\Api\oauth\facebook;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        $url = Socialite::with('facebook')->stateless()->redirect()->getTargetUrl();
        return \response()->json([
            'url' => $url
        ], 200);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::with('facebook')->stateless()->user();;
            $create['name'] = $user->getName();
            $create['email'] = $user->getEmail();
            $create['facebook_id'] = $user->getId();
            

        } catch (Exception $e) {
            return \response()->json([
                'code' => $e->getCode(),
                'message' => 'error'
            ]);
        }
    }
}
