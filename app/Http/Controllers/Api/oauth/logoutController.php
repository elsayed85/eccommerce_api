<?php

namespace App\Http\Controllers\Api\oauth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class logoutController extends Controller
{
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return responder()->success()->meta(['message' => 'logout sucffully', 'code' => 'logout-success']);
    }
}
