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

            if (empty($data['email']) || empty($data['password'])) {
                return back()->with('flash', [
                    'type' => 'danger',
                    'message' => 'Input credentials cannot be empty'
                ]);
            }
            //find user by email
            $userFromDb = User::where('email', $data['email'])->first();

            if (!isset($userFromDb)) {
                return back(400)->with('flash', [
                    'type' => 'danger',
                    'message' => 'Email does not exist'
                ]);
            }

            //check password
            if (!Hash::check($data['password'], $userFromDb->password)) {
                // Invalid credentials
                return back(400)->with('flash', [
                    'type' => 'danger',
                    'message' => 'Invalid credentials. Please try again'
                ]);
            }

            //user verified its account
            if (!$userFromDb->email_verified_at) {
                return back(400)->with('flash', [
                    'type' => 'danger',
                    'message' => 'Email not verified. Please check your email for verification'
                ]);
            }

            // $userFromDb->last_login = Carbon::now()->format('Y-m-d H:i:s');
            $userFromDb->save();
            if ($userFromDb->security_type == 0) {
                Auth::login($userFromDb, $data['remember']);
                define('USER_ID', $userFromDb->id);
                return redirect()->route('dashboard');
            }

            // Generete code for
            if ($userFromDb->two_factor_code == null) {
                $userFromDb->generateTwoFactorCode();
                if ($userFromDb->security_type == 1) {
                    $userFromDb->notify(new SendTwoFactorCodeEmail());
                } else {
                    $userFromDb->notify(new SendTwoFactorCodeSMS());
                }
                return back()->with('flash', [
                    'type' => 'info',
                    'message' => 'Please enter 2FA code'
                ])->with('twoFA', true);
            }

            // Send 2FA code
            $response = $userFromDb->checkTwoFactorCode($data['code']);
            if ($response == "Code is empty") {
                return back()->with('flash', [
                    'type' => 'danger',
                    'message' => 'Please enter 2FA code'
                ])->with('twoFA', true);
            } else if ($response == "Expired") {
                $userFromDb->resetTwoFactorCode();
                if ($userFromDb->security_type == 1) {
                    $userFromDb->notify(new SendTwoFactorCodeEmail());
                } else {
                    $userFromDb->notify(new SendTwoFactorCodeSMS());
                }
                return back()->with('flash', [
                    'type' => 'danger',
                    'message' => '2FA code is expired. Please try again later'
                ])->with('twoFA', true);
            } else if ($response == "Invalid code") {
                return back()->with('flash', [
                    'type' => 'danger',
                    'message' => 'Incorrect 2FA code'
                ])->with('twoFA', true);
            } else if ($response == "OK") {
                $userFromDb->resetTwoFactorCode();
                Auth::login($userFromDb, $data['remember']);
                return redirect()->route('dashboard');
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
            $content_type = !empty($request->file('profile_picture')) ? $request->file('profile_picture')->getMimeType() : null;

            if ($request->hasFile('profile_picture')) {
                $data['profile_picture'] = "data:$content_type;base64," . base64_encode(file_get_contents($request->file('profile_picture')));
            }
            $data['role_id'] = 1; //relative - family member

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
            ])->with('tab', 'privacy-settings');
        }

        $user->password = $request->input('new_password');

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Password was change successfully'
            ])->with('tab', 'privacy-settings');
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not change password. Please try again'
        ])->with('tab', 'privacy-settings');
    }

    public function changeSecurityType(Request $request)
    {
        $user = $request->user();

        $user->security_type = $request->input('security_type');

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Security type was changed successfully'
            ])->with('tab', 'privacy-settings');
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not change security type. Please try again'
        ])->with('tab', 'privacy-settings');
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
            ])->with('tab', 'notifications');
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not update notifications. Please try again'
        ])->with('tab', 'notifications');
    }

    public function changeProfilePicture(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->route('settings')->with('tab', 'profile');
        }
        $content_type = !empty($request->file('profile_picture')) ? $request->file('profile_picture')->getMimeType() : null;

        if ($request->hasFile('profile_picture')) {
            $picture = "data:$content_type;base64," . base64_encode(file_get_contents($request->file('profile_picture')));
        } else {
            $picture = null;
        }

        $user = $request->user();

        $user->profile_picture = $picture;

        if ($user->save()) {
            return redirect()->route('settings')->with('flash', [
                'type' => 'success',
                'message' => 'Profile picture updated successfully'
            ])->with('tab', 'profile');
        }

        return redirect()->route('settings')->with('flash', [
            'type' => 'danger',
            'message' => 'Could not update profile picture. Please try again'
        ])->with('tab', 'profile');
    }
}
