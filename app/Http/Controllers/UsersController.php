<?php

namespace App\Http\Controllers;

use App\Jobs\MailSender;
use App\Models\EmailConfirmation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UsersController extends Controller
{
    //
    public function index(Request $request)
    {
        return Inertia::render("Index");
    }

    /**
     * Login user method
     */
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('Authentication/Login');
        }

        if ($request->isMethod('post')) {
            $data = $request->only('email', 'password', 'remember');

            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember'])) {
                $user = Auth::user();

                if ($user->email_verified_at) {
                    // The user is logged in and the email is verified
                    $userFromDb = User::find($user->id);
                    $userFromDb->last_login = Carbon::now()->format('Y-m-d H:i:s');
                    $userFromDb->save();

                    define('USER_ID', $user->id);
                    return redirect()->route('home');
                } else {
                    // Logout the user since the email is not verified
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->with('flash', [
                        'type' => 'danger',
                        'message' => 'Email not verified. Please check your email for verification.'
                    ]);
                }
            } else {
                // Invalid credentials
                return back()->with('flash', [
                    'type' => 'danger',
                    'message' => 'Invalid credentials. Please try again.'
                ]);
            }
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Revoke the token that was used to authenticate the current request...
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('Authentication/Register');
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['profile_picture'] = file_get_contents($request->file('profile_picture'));
            $data['role'] = 1;

            if (User::where('email', $data['email'])->first() != null) {
                return redirect()->route('register')->with('flash', [
                    'type' => 'danger',
                    'message' => 'Email already registered.'
                ]);
            }

            $user = User::create($data);
            if ($user) {
                dispatch(new MailSender('EmailConfirmation', [
                    'email' => $data['email'],
                    'user_id' => $user->id
                ]));
            }

            return redirect()->route('login')->with('flash', [
                'type' => 'success',
                'message' => 'Registration successful.'
            ]);
        }
    }

    public function profile(Request $request)
    {
        return Inertia::render('User/Profile', [
            'profile_picture' => User::select(DB::raw('TO_BASE64(profile_picture) as profile_picture'))->where('id', $request->user()->id)->first()->profile_picture,
        ]);
    }

    /**
     * Confirm email of user
     *
     * @param string $token
     */
    public function confirmEmail($token)
    {
        //user is logged in before email confirmation of another account
        if (Auth::user() != null) {
            return redirect()->route('home')->with('flash', [
                'type' => 'warning',
                'message' => 'Please logout before confirming email.'
            ]);
        }

        $emailConfirmation = EmailConfirmation::where([
            ['token', '=', $token],
            ['is_confirmed', '=', 0]
        ])->first();

        if ($emailConfirmation == null) {
            return redirect()->route('login')->with('flash', [
                'type' => 'warning',
                'message' => 'Token does not exist.',
            ]);
        }

        try {
            $decryptedToken = Crypt::decrypt($emailConfirmation->token);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('home'); // Invalid or tampered token
        }

        //user is not logged in before email confirmation
        $user = User::find($decryptedToken);

        //user does not exist
        if ($user == null) {
            return redirect()->route('login');
        }

        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        $emailConfirmation->confirm();

        return redirect()->route('login')->with(
            'flash',
            [
                'type' => 'success',
                'message' => 'Email confirmation was successful.'
            ]
        );

        return redirect()->route('login');
    }
}
