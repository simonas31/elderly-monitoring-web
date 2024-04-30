<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityLogsControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_movement_detected_could_not_find_device(): void
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->post('/api/movementDetected', [
            'fell' => true,
            'device_name' => 'EW318237612'
        ]);

        $response->assertStatus(406);
    }

    public function test_movement_detected_validator_fails(): void
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->post('/api/movementDetected', [
            'fell' => '',
            'device_name' => 'EW318237612387'
        ]);

        $response->assertStatus(406);
    }

    public function test_movement_detected_fell(): void
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->post('/api/movementDetected', [
            'fell' => true,
            'device_name' => 'EW318237612387'
        ]);

        $response->assertStatus(200);
    }

    public function test_get_statistics_ok(): void
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->get('/api/getStatistics/' . 'EW318237612387');

        $response->assertStatus(200);
    }
}
