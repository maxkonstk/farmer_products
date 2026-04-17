<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@farmer-shop.test'],
            [
                'name' => 'Администратор магазина',
                'phone' => '+7 (900) 100-10-10',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'buyer@farmer-shop.test'],
            [
                'name' => 'Покупатель демонстрационный',
                'phone' => '+7 (900) 200-20-20',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}
