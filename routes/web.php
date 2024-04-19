<?php

use App\Http\Controllers\DevicesController;
use App\Http\Controllers\UsersController;
use App\Mail\EmailConfirmationMail;
use App\Mail\InvitationMail;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Unauthenticated routes
 */
Route::get('/confirm/{token}', [UsersController::class, 'confirmEmail'])->name('confirmEmail');
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', [UsersController::class, 'index'])->name('index');

    Route::get('/register/{token?}', [UsersController::class, 'register'])->name('register');
    Route::get('/login', [UsersController::class, 'login'])->name('login');

    Route::post('/register', [UsersController::class, 'register'])->name('register.post');
    Route::post('/login', [UsersController::class, 'login'])->name('login.post');
});

/**
 * Authenticated routes
 */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [UsersController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [UsersController::class, 'settings'])->name('settings');
    Route::get('/invite', [UsersController::class, 'invite'])->name('invite');
    Route::get('/supervisors', [UsersController::class, 'supervisors'])->name('supervisors');
    Route::get('/devices', [DevicesController::class, 'devices'])->name('devices');

    Route::post('/logout', [UsersController::class, 'logout'])->name('logout');
    Route::post('/changeDeviceName', [DevicesController::class, 'changeDeviceName'])->name('changeDeviceName');
    Route::post('/changePassword', [UsersController::class, 'changePassword'])->name('changePassword');
    Route::post('/changeSecurityType', [UsersController::class, 'changeSecurityType'])->name('changeSecurityType');
    Route::post('/toggleNotifications', [UsersController::class, 'toggleNotifications'])->name('toggleNotifications');
    Route::post('/changeProfilePicture', [UsersController::class, 'changeProfilePicture'])->name('changeProfilePicture');
    Route::post('/changePhoneNumber', [UsersController::class, 'changePhoneNumber'])->name('changePhoneNumber');
    Route::post('/sendInvitation', [UsersController::class, 'sendInvitation'])->name('sendInvitation');
    Route::post('/deleteUser', [UsersController::class, 'deleteUser'])->name('deleteUser');
    // //Folders
    // // Route::get('/', [FoldersController::class, 'index'])->name('folders');
    // Route::get('/folders/{folder_id}', [FoldersController::class, 'find'])->name('folders');
    // Route::post('/folders', [FoldersController::class, 'store'])->name('folders.store');
    // Route::put('/folders/{folder_id}', [FoldersController::class, 'update'])->name('folders.update');
    // Route::delete('/folders/{folder_id}', [FoldersController::class, 'destroy'])->name('folders.delete');

    // //Files
    // Route::post('/files', [FilesController::class, 'store'])->name('files.store');
    // Route::post('/files/{file_id}', [FilesController::class, 'update'])->name('files.update');
    // Route::delete('/files/{file_id}', [FilesController::class, 'destroy'])->name('files.delete');

    // Route::get('/profile', [UsersController::class, 'profile'])->name('user.profile');
});

Route::get('{slug}', function () {
    return redirect('/');
});
