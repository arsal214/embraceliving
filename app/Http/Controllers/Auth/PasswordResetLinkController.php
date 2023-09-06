<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\DynamicEmail;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{



    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            $user = User::where('email', $request->email)->first();

            Password::deleteToken($user);
            $token = Password::createToken($user);

            $link = route('password.reset', [$token, 'email' => $request->email]);
            $template = getSettings('forgot_email_template');

            $arr = array('{$link}' => $link);

            $data = strtr($template, $arr);
            Mail::to($request->email)->send(new DynamicEmail($data));
            return back()->with('success', 'Password Reset Link Sent Successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->withErrors(['msg' => $th->getMessage()]);
        }


        // dd($status);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // return $status == Password::RESET_LINK_SENT
        //     ? back()->with('status', __($status))
        //     : back()->withInput($request->only('email'))
        //     ->withErrors(['email' => __($status)]);
    }
}
