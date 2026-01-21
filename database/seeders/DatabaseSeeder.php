<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Pet;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create staff users
        User::factory()->staff()->create([
            'name' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),

        ]);

        // Create additional random users
        User::factory(10)->create();

        // Create clients with pets
        Client::factory(20)->create()->each(function ($client) {
            Pet::factory(rand(1, 3))->create([
                'client_id' => $client->id,
            ]);
        });
    }
}
