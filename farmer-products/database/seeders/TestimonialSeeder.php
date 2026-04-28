<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('shop.testimonials', []) as $index => $testimonial) {
            Testimonial::query()->updateOrCreate(
                ['author' => $testimonial['author'], 'quote' => $testimonial['quote']],
                [
                    'role' => $testimonial['role'] ?? null,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
