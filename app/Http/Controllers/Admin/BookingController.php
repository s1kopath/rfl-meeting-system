<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['user', 'meetingRoom'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->when($request->room, function ($query, $room) {
                return $query->where('meeting_room_id', $room);
            })
            ->when($request->user, function ($query, $user) {
                return $query->where('user_id', $user);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'totalBookings' => Booking::count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'confirmedBookings' => Booking::where('status', 'confirmed')->count(),
            'rejectedBookings' => Booking::where('status', 'rejected')->count(),
        ];

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings->items(),
            'rooms' => MeetingRoom::all(),
            'users' => User::all(),
            'stats' => $stats,
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'from' => $bookings->firstItem(),
                'to' => $bookings->lastItem(),
                'prev_page_url' => $bookings->previousPageUrl(),
                'next_page_url' => $bookings->nextPageUrl(),
            ],
            'filters' => $request->only(['status', 'date', 'room', 'user'])
        ]);
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'meetingRoom']);

        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking
        ]);
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'status' => 'confirmed',
            'admin_notes' => request('admin_notes', 'Approved by admin')
        ]);

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }

    public function reject(Booking $booking)
    {
        $validated = request()->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes']
        ]);

        return redirect()->back()->with('success', 'Booking rejected successfully.');
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees_count' => 'required|integer|min:1',
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'status' => 'required|in:pending,confirmed,rejected,cancelled',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $booking->update($validated);

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }
}
