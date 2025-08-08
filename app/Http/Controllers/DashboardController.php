<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Prepare stats data
        $stats = [
            'totalRooms' => MeetingRoom::active()->count(),
            'myBookings' => $user ? $user->bookings()->count() : 0,
            'pendingBookings' => Booking::byStatus('pending')->count(),
            'monthlyBookings' => Booking::whereMonth('created_at', now()->month)->count(),
        ];

        // Prepare recent bookings
        $recentBookings = [];
        if ($user) {
            $recentBookings = $user->bookings()
                ->with('meetingRoom')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'title' => $booking->title,
                        'date' => $booking->date,
                        'formatted_time' => $booking->formatted_time,
                        'status' => $booking->status,
                    ];
                });
        }

        // Prepare available rooms
        $availableRooms = MeetingRoom::active()
            ->limit(5)
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'seats' => $room->seats,
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'availableRooms' => $availableRooms,
        ]);
    }
}
