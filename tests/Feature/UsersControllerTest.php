<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Core\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class UsersControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dashboard(): void
    {
        $user = User::find(1)->first();
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_login_get(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_login_post_empty_credentials(): void
    {
        $response = $this->post('/login');

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_email_does_not_exist(): void
    {
        $response = $this->post('/login', [
            'email' => 'test123555@gmail.com',
            'password' => 'text',
            'remember' => false,
            'code' => null
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_incorrect_pass(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => '123',
            'remember' => false,
            'code' => null
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_email_not_verified(): void
    {
        $response = $this->post('/login', [
            'email' => 'naujass12@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => null
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_no_security_type(): void
    {
        $response = $this->post('/login', [
            'email' => 'naujass1234@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => null
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_email_set_code(): void
    {
        $user = User::where('id', 1)->first();
        $user->resetTwoFactorCode();
        $user->save();
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => null
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_enter_code_empty(): void
    {
        $user = User::all()->where('id', '=', 1)->first();
        $user->two_factor_code = 123111;
        $user->save();
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => null
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_enter_code_expired(): void
    {
        $user = User::all()->where('id', '=', 5)->first();
        $user->resetTwoFactorCode();
        $user->generateTwoFactorCode();
        $user->two_factor_expires_at = now()->addMinutes(20);
        $user->save();
        $response = $this->post('/login', [
            'email' => 'naujass123@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => $user->two_factor_code
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_enter_code_incorrect(): void
    {
        $user = User::all()->where('id', '=', 5)->first();
        $user->resetTwoFactorCode();
        $user->generateTwoFactorCode();
        $user->save();
        $response = $this->post('/login', [
            'email' => 'naujass123@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => 123
        ]);

        $response->assertStatus(302);
    }

    public function test_login_post_credentials_enter_code_correct(): void
    {
        $user = User::all()->where('id', '=', 5)->first();
        $user->resetTwoFactorCode();
        $user->generateTwoFactorCode();
        $user->save();
        $response = $this->post('/login', [
            'email' => 'naujass123@gmail.com',
            'password' => 'test',
            'remember' => false,
            'code' => $user->two_factor_code
        ]);

        $response->assertStatus(302);
    }

    public function test_logout(): void
    {
        $user = User::where('id', 1)->first();
        $response = $this->actingAs($user)->post('/logout');
        $response->assertStatus(302);
    }

    public function test_register(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_register_post_email_exists(): void
    {
        Storage::fake('public'); // This fakes the disk where you are uploading your file, in this case, 'public'

        $file = UploadedFile::fake()->create('test.png', 10);
        $response = $this->post('/register', [
            'name' => 'test',
            'surname' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'test',
            'security_type' => 0,
            'role_id' => 0,
            'phone_number' => '123',
            'date_of_birth' => now(),
            'profile_picture' => $file
        ]);

        $response->assertStatus(302);
    }

    public function test_register_post(): void
    {
        Storage::fake('public'); // This fakes the disk where you are uploading your file, in this case, 'public'

        $file = UploadedFile::fake()->create('test.png', 10);
        $response = $this->post('/register', [
            'name' => 'test',
            'surname' => 'test',
            'email' => 'random' . rand(1, 1000000000) . '@gmail.com',
            'password' => 'test',
            'security_type' => 0,
            'role_id' => 0,
            'phone_number' => '123',
            'date_of_birth' => now(),
            'profile_picture' => $file
        ]);

        $response->assertStatus(302);
    }

    public function test_confim_email_failed_user_logged_in(): void
    {
        $user = User::find(1)->first();
        $response = $this->actingAs($user)->get('/confirm/test');

        $response->assertStatus(302);
    }

    public function test_confim_email_failed_email_not_found(): void
    {
        $response = $this->get('/confirm/zaza');

        $response->assertStatus(302);
    }

    public function test_confim_email_failed_to_decrypt(): void
    {
        $response = $this->get('/confirm/test');

        $response->assertStatus(302);
    }

    public function test_confim_email_failed_to_find_user(): void
    {
        $response = $this->get('/confirm/eyJpdiI6ImY5dnlJSnpNVmVTZ1l2VzBvUmcyM3c9PSIsInZhbHVlIjoiOERoN2l0U1dyMnlIVDcxWVFkUmcydz09IiwibWFjIjoiZjA5MWJlNDk3Njk1ZmFhYmZkNTIxZWZlMzliNDU0NWFhZWMyODY2YjA3Y2EyZmY5Njg4Y2E4NDBkNmRjZTY3ZCIsInRhZyI6IiJ9');

        $response->assertStatus(302);
    }

    public function test_confim_email_confirm(): void
    {
        $response = $this->get('/confirm/eyJpdiI6IlQ2czJXWDhBMi9nQjFwLzFrNGZ2VUE9PSIsInZhbHVlIjoiQnRPOWdiSlNML3FMS2dsUVB4YjRCUT09IiwibWFjIjoiODU5M2Y2ZDY4NmU5Y2ZkNDI1YTU1ZDI3MDg3ODQxZDc3MjRhYjlkMmYyNGUxYmU1OTczODJjMThlODJkODY3ZiIsInRhZyI6IiJ9');

        $response->assertStatus(302);
    }

    public function test_get_settings(): void
    {
        $user = User::find(1)->first();
        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
    }

    public function test_password_change_success(): void
    {
        $user = User::find(1)->first();
        $response = $this->actingAs($user)->post('/changePassword', [
            'current_password' => 'test2',
            'new_password' => 'test2'
        ]);

        $response->assertStatus(302);
    }

    public function test_password_change_fail_incorrect_pass(): void
    {
        $user = User::find(1)->first();
        $response = $this->actingAs($user)->post('/changePassword', [
            'current_password' => 'test23',
            'new_password' => 'test2'
        ]);

        $response->assertStatus(302);
    }

    public function test_change_security_type(): void
    {
        $user = User::find(1)->first();
        $response = $this->actingAs($user)->post('/changeSecurityType', [
            'security_type' => '0',
        ]);

        $response->assertStatus(302);
    }

    public function test_change_profil_pic_type_success(): void
    {
        $user = User::where('id', 5)->first();
        Storage::fake('public'); // This fakes the disk where you are uploading your file, in this case, 'public'

        $file = UploadedFile::fake()->create('test.png', 10);
        $response = $this->actingAs($user)->post('/changeProfilePicture', [
            'profile_picture' => $file,
        ]);

        $response->assertStatus(302);
    }

    public function test_change_profil_pic_type_success_2(): void
    {
        $user = User::where('id', 5)->first();
        Storage::fake('public'); // This fakes the disk where you are uploading your file, in this case, 'public'

        $file = UploadedFile::fake()->create('test.png', 10);
        $response = $this->actingAs($user)->post('/changeProfilePicture');

        $response->assertStatus(302);
    }

    public function test_change_phone(): void
    {
        $user = User::where('id', 5)->first();
        $response = $this->actingAs($user)->post('/changePhoneNumber', [
            'phone_number' => '123',
        ]);

        $response->assertStatus(302);
    }

    public function test_invite_good(): void
    {
        $user = User::where('id', 1)->first();
        $response = $this->actingAs($user)->get('/invite');

        $response->assertStatus(200);
    }

    public function test_invite_bad(): void
    {
        $user = User::where('id', 5)->first();
        $response = $this->actingAs($user)->get('/invite');

        $response->assertStatus(302);
    }

    public function test_send_invite_user_exists(): void
    {
        $user = User::where('id', 1)->first();
        $response = $this->actingAs($user)->post('/sendInvitation', [
            'email' => 'test@gmail.com'
        ]);

        $response->assertStatus(302);
    }

    public function test_send_invite_success(): void
    {
        $user = User::where('id', 1)->first();
        $response = $this->actingAs($user)->post('/sendInvitation', [
            'email' => 'testas@gmail.com'
        ]);

        $response->assertStatus(302);
    }

    public function test_get_supervisors(): void
    {
        $user = User::where('id', 1)->first();
        $response = $this->actingAs($user)->get('/supervisors');

        $response->assertStatus(200);
    }
}
