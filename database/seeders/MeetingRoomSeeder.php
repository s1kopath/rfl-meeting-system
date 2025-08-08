<?php

namespace Database\Seeders;

use App\Models\MeetingRoom;
use Illuminate\Database\Seeder;

class MeetingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Conference Room A',
                'description' => 'Large conference room with modern amenities',
                'seats' => 20,
                'tables' => 4,
                'ac_units' => 2,
                'washroom_facilities' => 1,
                'has_podium' => true,
                'has_sound_system' => true,
                'has_projector' => true,
                'monitors' => 2,
                'tv_screens' => 1,
                'has_ethernet' => true,
                'has_wifi' => true,
            ],
            [
                'name' => 'Meeting Room B',
                'description' => 'Medium-sized meeting room for team discussions',
                'seats' => 12,
                'tables' => 2,
                'ac_units' => 1,
                'washroom_facilities' => 1,
                'has_podium' => false,
                'has_sound_system' => true,
                'has_projector' => true,
                'monitors' => 1,
                'tv_screens' => 0,
                'has_ethernet' => true,
                'has_wifi' => true,
            ],
            [
                'name' => 'Board Room',
                'description' => 'Executive board room with premium facilities',
                'seats' => 8,
                'tables' => 1,
                'ac_units' => 1,
                'washroom_facilities' => 1,
                'has_podium' => true,
                'has_sound_system' => true,
                'has_projector' => true,
                'monitors' => 1,
                'tv_screens' => 1,
                'has_ethernet' => true,
                'has_wifi' => true,
            ],
            [
                'name' => 'Training Room',
                'description' => 'Training room with presentation facilities',
                'seats' => 15,
                'tables' => 3,
                'ac_units' => 1,
                'washroom_facilities' => 1,
                'has_podium' => true,
                'has_sound_system' => true,
                'has_projector' => true,
                'monitors' => 0,
                'tv_screens' => 1,
                'has_ethernet' => false,
                'has_wifi' => true,
            ],
            [
                'name' => 'Small Meeting Room',
                'description' => 'Intimate meeting room for small groups',
                'seats' => 6,
                'tables' => 1,
                'ac_units' => 1,
                'washroom_facilities' => 1,
                'has_podium' => false,
                'has_sound_system' => false,
                'has_projector' => false,
                'monitors' => 0,
                'tv_screens' => 0,
                'has_ethernet' => true,
                'has_wifi' => true,
            ],
        ];

        foreach ($rooms as $room) {
            MeetingRoom::create($room);
        }
    }
} 