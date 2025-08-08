<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = MeetingRoom::active()
            ->when($request->capacity, function ($query, $capacity) {
                $ranges = [
                    '1-5' => [1, 5],
                    '6-10' => [6, 10],
                    '11-20' => [11, 20],
                    '20+' => [21, 999]
                ];
                
                if (isset($ranges[$capacity])) {
                    [$min, $max] = $ranges[$capacity];
                    return $query->whereBetween('seats', [$min, $max]);
                }
                
                return $query;
            })
            ->when($request->amenity, function ($query, $amenity) {
                return $query->where("has_{$amenity}", true);
            })
            ->get();

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
            'filters' => $request->only(['capacity', 'amenity', 'date'])
        ]);
    }

    public function show(MeetingRoom $room)
    {
        return Inertia::render('Rooms/Show', [
            'room' => $room->load(['bookings' => function ($query) {
                $query->where('status', 'confirmed')
                    ->where('date', '>=', now()->toDateString())
                    ->orderBy('date')
                    ->orderBy('start_time');
            }])
        ]);
    }
}
