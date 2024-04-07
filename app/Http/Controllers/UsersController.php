<?php

namespace App\Http\Controllers;

use App\Jobs\MailSender;
use App\Models\EmailConfirmation;
use App\Models\User;
use App\Notifications\SendTwoFactorCodeEmail;
use App\Notifications\SendTwoFactorCodeSMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{
    //
    public function index(Request $request)
    {
        return Inertia::render("Index");
    }

    public function dashboard(Request $request)
    {
        //pass custom parameters to page
        return Inertia::render('User/Dashboard');
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
            $data = $request->only('email', 'password', 'remember', 'code');

            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember'])) {
                $user = Auth::user();

                if ($user->email_verified_at) {
                    // The user is logged in and the email is verified
                    $userFromDb = User::find($user->id);
                    $userFromDb->last_login = Carbon::now()->format('Y-m-d H:i:s');
                    $userFromDb->save();
                    if ($userFromDb->security_type == 0) {
                        define('USER_ID', $user->id);
                        return redirect()->route('dashboard');
                    }

                    if ($user->two_factor_code == null) {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        $userFromDb->generateTwoFactorCode();
                        if ($userFromDb->security_type == 1) {
                            $userFromDb->notify(new SendTwoFactorCodeEmail());
                        } else {
                            $userFromDb->notify(new SendTwoFactorCodeSMS());
                        }
                        return back()->with('flash', [
                            'type' => 'info',
                            'message' => 'Please enter 2FA code'
                        ]);
                    }

                    // Send 2FA code
                    $response = $userFromDb->checkTwoFactorCode($data['code']);
                    if ($response == "Code is empty") {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return back()->with('flash', [
                            'type' => 'danger',
                            'message' => 'Please enter 2FA code'
                        ]);
                    } else if ($response == "Expired") {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return back()->with('flash', [
                            'type' => 'danger',
                            'message' => '2FA code is expired. Please try again later'
                        ]);
                    } else if ($response == "Invalid code") {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return back()->with('flash', [
                            'type' => 'danger',
                            'message' => 'Incorrect 2FA code'
                        ]);
                    } else if ($response == "OK") {
                        $userFromDb->resetTwoFactorCode();
                        return redirect()->route('dashboard');
                    }
                    //
                } else {
                    // Logout the user since the email is not verified
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->with('flash', [
                        'type' => 'danger',
                        'message' => 'Email not verified. Please check your email for verification'
                    ]);
                }
            } else {
                // Invalid credentials
                return back()->with('flash', [
                    'type' => 'danger',
                    'message' => 'Invalid credentials. Please try again'
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
            return redirect()->route('index'); // Invalid or tampered token
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

    public function settings(Request $request)
    {
        return Inertia::render('User/Settings', [
            'user' => $request->user()
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'danger',
                'message' => 'Incorrect current password'
            ]);
        }

        $user->password = $request->input('new_password');

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Password was change successfully'
            ]);
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not change password. Please try again'
        ]);
    }

    public function changeSecurityType(Request $request)
    {
        $user = $request->user();

        $user->security_type = $request->input('security_type');

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Security type was changed successfully'
            ]);
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not change security type. Please try again'
        ]);
    }

    /**
     * Toggle user notifications
     */
    public function toggleNotifications(Request $request)
    {
        $user = $request->user();

        $user->fall_notifications = $request->input('fall_alert');

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Notifications were updated successfully'
            ]);
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not update notifications. Please try again'
        ]);
    }

    public function changeProfilePicture(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->route('settings');
        }

        $picture = file_get_contents($request->file('profile_picture'));

        $user = $request->user();

        $user->profile_picture = $picture;

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Profile picture updated successfully'
            ]);
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not update profile picture. Please try again'
        ]);
    }
}
