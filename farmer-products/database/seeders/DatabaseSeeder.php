<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            FarmerSeeder::class,
            TestimonialSeeder::class,
            FaqItemSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
