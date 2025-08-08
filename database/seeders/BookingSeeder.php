<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $rooms = MeetingRoom::all();

        if ($users->isEmpty() || $rooms->isEmpty()) {
            return;
        }

        // Create sample bookings
        $bookings = [
            [
                'title' => 'Team Meeting',
                'description' => 'Weekly team sync meeting',
                'date' => now()->addDays(1)->toDateString(),
                'start_time' => '09:00',
                'end_time' => '10:00',
                'attendees_count' => 8,
                'status' => 'pending',
                'user_id' => $users->where('role', 'user')->first()->id,
                'meeting_room_id' => $rooms->first()->id,
            ],
            [
                'title' => 'Client Presentation',
                'description' => 'Presentation to potential client',
                'date' => now()->addDays(2)->toDateString(),
                'start_time' => '14:00',
                'end_time' => '15:30',
                'attendees_count' => 12,
                'status' => 'confirmed',
                'admin_notes' => 'Approved - Good room for presentation',
                'user_id' => $users->where('role', 'user')->first()->id,
                'meeting_room_id' => $rooms->first()->id,
            ],
            [
                'title' => 'Training Session',
                'description' => 'New employee training',
                'date' => now()->addDays(3)->toDateString(),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'attendees_count' => 15,
                'status' => 'rejected',
                'admin_notes' => 'Rejected - Room too small for 15 people',
                'user_id' => $users->where('role', 'user')->first()->id,
                'meeting_room_id' => $rooms->first()->id,
            ],
            [
                'title' => 'Board Meeting',
                'description' => 'Monthly board meeting',
                'date' => now()->addDays(5)->toDateString(),
                'start_time' => '16:00',
                'end_time' => '18:00',
                'attendees_count' => 6,
                'status' => 'pending',
                'user_id' => $users->where('role', 'staff')->first()->id,
                'meeting_room_id' => $rooms->last()->id,
            ],
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }
    }
}
