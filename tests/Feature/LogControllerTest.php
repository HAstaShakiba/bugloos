<?php

namespace Tests\Feature;

use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LogControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_see_zero_count_logs()
    {
        $user = user::factory()->create();

        $response = $this->actingAs($user)->getJson(route('logs'));

        $response->assertOk();

        $response->assertJsonStructure(['count']);
        $response->assertJson(['count' => 0]);
    }

    public function test_user_can_see_all_stored_count_logs()
    {
        $user = user::factory()->create();
        Log::factory()->times(20)->create();

        $response = $this->actingAs($user)->getJson(route('logs'));

        $response->assertOk();

        $response->assertJsonStructure(['count']);
        $response->assertJson(['count' => 20]);
    }

    public function test_user_can_filter_by_service_names()
    {
        $user = user::factory()->create();
        Log::factory()->times(10)->create(['service' => 'invoice_service']);
        Log::factory()->times(5)->create(['service' => 'user_service']);
        Log::factory()->times(3)->create(['service' => 'order-service']);

        $response = $this->actingAs($user)->getJson(route('logs', [
            'serviceNames' => ['order-service', 'user_service']
        ]));

        $response->assertOk();

        $response->assertJsonStructure(['count']);
        $response->assertJson(['count' => 8]);
    }

    public function test_user_can_filter_by_status_code()
    {
        $user = user::factory()->create();
        Log::factory()->times(10)->create(['status' => 200]);
        Log::factory()->times(3)->create(['status' => 201]);

        $response = $this->actingAs($user)->getJson(route('logs', [
            'status' => 201,
        ]));

        $response->assertOk();

        $response->assertJsonStructure(['count']);
        $response->assertJson(['count' => 3]);
    }

    public function test_user_can_filter_by_start_date_called_service()
    {
        $user = user::factory()->create();
        Log::factory()->times(2)->create(['called_at' => $this->faker->dateTimeBetween('-2 years', '-1 year')]);
        Log::factory()->times(3)->create(['called_at' => $this->faker->dateTimeBetween('-1 years')]);

        $response = $this->actingAs($user)->getJson(route('logs', [
            'startDate' => now()->subYear()->format('Y-m-d h:i:s'),
        ]));

        $response->assertOk();

        $response->assertJsonStructure(['count']);
        $response->assertJson(['count' => 3]);
    }

    public function test_user_can_filter_by_end_date_called_service()
    {
        $user = user::factory()->create();
        Log::factory()->times(2)->create(['called_at' => $this->faker->dateTimeBetween('-2 years', '-1 year')]);
        Log::factory()->times(3)->create(['called_at' => $this->faker->dateTimeBetween('-1 years')]);

        $this->travel(-1)->years();
        $response = $this->actingAs($user)->getJson(route('logs', [
            'endDate' => now()->format('Y-m-d h:i:s'),
        ]));

        $response->assertOk();

        $response->assertJsonStructure(['count']);
        $response->assertJson(['count' => 2]);
        $this->travelBack();
    }
}
