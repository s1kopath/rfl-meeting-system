<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::withCount(['bookings' => function ($query) {
            $query->where('date', '>=', now()->toDateString());
        }])->get();

        $stats = [
            'totalRooms' => MeetingRoom::count(),
            'availableRooms' => MeetingRoom::active()->count(),
            'bookedToday' => MeetingRoom::whereHas('bookings', function ($query) {
                $query->where('date', now()->toDateString())
                    ->where('status', 'confirmed');
            })->count(),
            'totalCapacity' => MeetingRoom::sum('seats'),
        ];

        return Inertia::render('Admin/Rooms/Index', [
            'rooms' => $rooms,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'seats' => 'required|integer|min:1',
            'tables' => 'required|integer|min:1',
            'ac_units' => 'required|integer|min:1',
            'washroom_facilities' => 'required|integer|min:1',
            'has_podium' => 'boolean',
            'has_sound_system' => 'boolean',
            'has_projector' => 'boolean',
            'monitors' => 'integer|min:0',
            'tv_screens' => 'integer|min:0',
            'has_ethernet' => 'boolean',
            'has_wifi' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $room = MeetingRoom::create($validated);

        // Handle image upload
        if ($request->hasFile('image')) {
            $room->uploadImage($request->file('image'));
        }

        return redirect()->back()->with('success', 'Meeting room created successfully.');
    }

    public function edit(MeetingRoom $room)
    {
        return Inertia::render('Admin/Rooms/Edit', [
            'room' => $room
        ]);
    }

    public function update(Request $request, MeetingRoom $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'seats' => 'required|integer|min:1',
            'tables' => 'required|integer|min:1',
            'ac_units' => 'required|integer|min:1',
            'washroom_facilities' => 'required|integer|min:1',
            'has_podium' => 'boolean',
            'has_sound_system' => 'boolean',
            'has_projector' => 'boolean',
            'monitors' => 'integer|min:0',
            'tv_screens' => 'integer|min:0',
            'has_ethernet' => 'boolean',
            'has_wifi' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $room->update($validated);

        // Handle image upload
        if ($request->hasFile('image')) {
            $room->uploadImage($request->file('image'));
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Meeting room updated successfully.');
    }

    public function destroy(MeetingRoom $room)
    {
        // Check if room has any bookings
        if ($room->bookings()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete room with existing bookings.');
        }

        $room->delete();

        return redirect()->back()->with('success', 'Meeting room deleted successfully.');
    }

    public function toggleStatus(MeetingRoom $room)
    {
        $room->update(['is_active' => !$room->is_active]);

        $status = $room->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Meeting room {$status} successfully.");
    }
}
