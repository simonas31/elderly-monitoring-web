<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DevicesControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_fail_post_device(): void
    {
        $response = $this->post('/api/registerDevice', [
            'email' => '',
            'password' => 'test',
            'device' => 'EW318237612387'
        ]);

        $response->assertStatus(406);
    }

    public function test_post_device_user_404(): void
    {
        $response = $this->post('/api/registerDevice', [
            'email' => 'testasdasd@gmail.com',
            'password' => 'test',
            'device' => 'EW318237612387'
        ]);

        $response->assertStatus(401);
    }

    public function test_post_device_user_pass_404(): void
    {
        $response = $this->post('/api/registerDevice', [
            'email' => 'test@gmail.com',
            'password' => 'testasdasd',
            'device' => 'EW318237612387'
        ]);

        $response->assertStatus(401);
    }

    public function test_post_device_exists_404(): void
    {
        $response = $this->post('/api/registerDevice', [
            'email' => 'test@gmail.com',
            'password' => 'test2',
            'device' => 'EW318237612387'
        ]);

        $response->assertStatus(200);
    }

    public function test_post_device_200(): void
    {
        $response = $this->post('/api/registerDevice', [
            'email' => 'test@gmail.com',
            'password' => 'test2',
            'device' => 'EW318237612387' . rand(1, 100000000)
        ]);

        $response->assertStatus(200);
    }

    public function test_change_device_name_doesnt_exist(): void
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->post('/changeDeviceName', [
            'device_name' => 'EW318232387',
            'new_device_name' => 'EW318232387'
        ]);

        $response->assertStatus(302);
    }

    public function test_change_device_name(): void
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->post('/changeDeviceName', [
            'device_name' => 'EW318237612387',
            'new_device_name' => 'EW318237612387'
        ]);

        $response->assertStatus(302);
    }
}
