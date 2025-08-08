<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'attendees_count',
        'status',
        'cancellation_reason',
        'admin_notes',
        'is_rescheduled',
        'original_booking_id',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'is_rescheduled' => 'boolean',
    ];

    protected $appends = ['formatted_time'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meetingRoom(): BelongsTo
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    public function originalBooking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'original_booking_id');
    }

    public function rescheduledBookings()
    {
        return $this->hasMany(Booking::class, 'original_booking_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('date')
                    ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString())
                    ->orderBy('date', 'desc')
                    ->orderBy('start_time', 'desc');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }

    public function getFormattedTimeAttribute()
    {
        $start = Carbon::parse($this->start_time)->format('H:i');
        $end = Carbon::parse($this->end_time)->format('H:i');
        return "{$start} - {$end}";
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'confirmed' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->date > now()->toDateString();
    }

    public function canBeRescheduled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->date > now()->toDateString();
    }
} 