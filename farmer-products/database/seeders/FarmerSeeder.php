<?php

namespace Database\Seeders;

use App\Models\Farmer;
use Illuminate\Database\Seeder;

class FarmerSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('shop.farmers', []) as $index => $farmer) {
            Farmer::query()->updateOrCreate(
                ['name' => $farmer['name']],
                [
                    'location' => $farmer['location'] ?? null,
                    'specialty' => $farmer['specialty'] ?? null,
                    'story' => $farmer['story'] ?? '',
                    'image' => $farmer['image'] ?? null,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
