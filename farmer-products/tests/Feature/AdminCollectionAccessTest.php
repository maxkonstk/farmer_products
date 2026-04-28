<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCollectionAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_collections_section(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)->get(route('admin.collections.index'))->assertOk();
    }
}
