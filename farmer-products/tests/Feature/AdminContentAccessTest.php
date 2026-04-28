<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_content_management_sections(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)->get(route('admin.farmers.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.testimonials.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.faq-items.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.promos.index'))->assertOk();
    }
}
