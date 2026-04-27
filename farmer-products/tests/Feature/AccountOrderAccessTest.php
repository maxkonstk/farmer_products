<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountOrderAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_open_orders_index(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('account.orders.index'));

        $response->assertOk();
    }

    public function test_unverified_user_is_redirected_to_email_verification_notice(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('account.orders.index'));

        $response->assertRedirect(route('verification.notice'));
    }
}
