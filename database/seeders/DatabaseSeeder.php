<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('654321'),
            'type' => User::TYPE_ADMIN,
        ]);

        User::create([
            'name' => 'Osis',
            'email' => 'osis@gmail.com',
            'password' => Hash::make('123456'),
            'type' => User::TYPE_USER,
        ]);

        Event::create([
            'name' => 'RAPAT PLENO 2025/2026',
            'date' => now(),
        ]);
    }
}
