<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rfl.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@rfl.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create sample regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@rfl.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Seed meeting rooms
        $this->call([
            MeetingRoomSeeder::class,
        ]);

        // Seed sample bookings
        $this->call([
            BookingSeeder::class,
        ]);
    }
}
