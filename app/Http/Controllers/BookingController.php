<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Auth::user()->bookings()
            ->with(['meetingRoom'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->when($request->room, function ($query, $room) {
                return $query->where('meeting_room_id', $room);
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return Inertia::render('Bookings/Index', [
            'bookings' => $bookings->items(),
            'rooms' => MeetingRoom::active()->get(),
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
            'filters' => $request->only(['status', 'date', 'room'])
        ]);
    }

    public function create(Request $request)
    {
        $rooms = MeetingRoom::active()->get();

        // Get existing bookings for the selected date if provided
        $existingBookings = collect();
        if ($request->has('date') && $request->date) {
            // Convert the date to the proper format for database query
            $queryDate = date('Y-m-d', strtotime($request->date));

            $existingBookings = Booking::whereDate('date', $queryDate)
                ->whereIn('status', ['confirmed', 'pending'])
                ->with(['meetingRoom'])
                ->get()
                ->groupBy('meeting_room_id');
        }

        return Inertia::render('Bookings/Create', [
            'rooms' => $rooms,
            'existingBookings' => $existingBookings,
            'selectedDate' => $request->date
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees_count' => 'required|integer|min:1',
            'meeting_room_id' => 'required|exists:meeting_rooms,id'
        ]);

        // Check if the room is available for the selected time
        $conflictingBookings = Booking::where('meeting_room_id', $validated['meeting_room_id'])
            ->whereDate('date', $validated['date'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($conflictingBookings) {
            return back()->withErrors([
                'time_conflict' => 'The selected room is not available for the chosen time slot. Please select a different time or room.'
            ]);
        }

        $booking = Auth::user()->bookings()->create([
            ...$validated,
            'status' => 'pending'
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully. Waiting for approval.');
    }

    public function show(Booking $booking)
    {
        // Check if user owns this booking or is admin/staff
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403);
        }

        return Inertia::render('Bookings/Show', [
            'booking' => $booking->load(['meetingRoom', 'user'])
        ]);
    }

    public function edit(Booking $booking)
    {
        // Check if user owns this booking or is admin/staff
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403);
        }

        return Inertia::render('Bookings/Edit', [
            'booking' => $booking->load(['meetingRoom']),
            'rooms' => MeetingRoom::active()->get()
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        // Check if user owns this booking or is admin/staff
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendees_count' => 'required|integer|min:1',
            'meeting_room_id' => 'required|exists:meeting_rooms,id'
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        // Check if user owns this booking or is admin/staff
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Cancelled by user'
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    public function history(Request $request)
    {
        $bookings = Auth::user()->bookings()
            ->with(['meetingRoom'])
            ->whereIn('status', ['confirmed', 'rejected', 'cancelled'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return Inertia::render('Bookings/History', [
            'bookings' => $bookings->items(),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'from' => $bookings->firstItem(),
                'to' => $bookings->lastItem(),
                'prev_page_url' => $bookings->previousPageUrl(),
                'next_page_url' => $bookings->nextPageUrl(),
            ]
        ]);
    }
}
