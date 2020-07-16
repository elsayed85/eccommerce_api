<?php


namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Controllers\Controller;

use App\User;
use App\wishlist;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;

class VerificationApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('show');
    }
    use VerifiesEmails;
    /**
     * Show the email verification notice.
     *
     */
    public function show(Request $request)
    {
        // if ($request->user()->hasVerifiedEmail()) {
        //     return response()->json('Email Verified');
        // } else {
        //     return response()->json('Email not verified');
        // }
    }
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        // $userID = $request['id'];
        // $user = User::findOrFail($userID);
        // if ($user->hasVerifiedEmail()) {
        //     return responder()->error('already-verified', 'User already have verified email!');
        // }
        // $date = date('Y-m-d g:i:s');
        // $user->email_verified_at = $date;
        // // to enable the 'email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        // $user->save();
        // return response()->json('Email verified!');
        $user = User::find($request->route('id'));
        if (is_null($user)) {
            return responder()->error(404, 'not found');
        }
        $user = auth()->loginUsingId($request->route('id'));

        if (($request->route('id') != $request->user()->getKey()) || (sha1($user->email) !=
            collect(request()->segments())->last())) {
            return \response()->json(['message' => 'error , send message again']);
        }
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified']);
            // return redirect($this->redirectPath());
        }
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        // create cart and wishlist for the user
        if ($user->cart()->count() == 0 && $user->wishlist()->count() == 0) {
            Cart::create([
                'id' => md5(uniqid(rand(), true)),
                'user_id' => $user->id,
            ]);
            wishlist::create([
                'id' => md5(uniqid(rand(), true)),
                'user_id' => $user->id,
            ]);
        }
        return response(['message' => 'Successfully verified']);
    }
    /**
     * Resend the email verification notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user->hasVerifiedEmail()) {
            return responder()->success()->meta(
                [
                    'message' => ['code' => 'already-verified', 'message' => 'User already have verified email!']
                ]
            );
            // return redirect($this->redirectPath());
        }
        $user->sendApiEmailVerificationNotification();
        return responder()->success()->meta(['message' => 'The notification has been resubmitted']);
        // return back()->with('resent', true);
    }
}
