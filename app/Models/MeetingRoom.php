<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MeetingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
        'seats',
        'tables',
        'ac_units',
        'washroom_facilities',
        'has_podium',
        'has_sound_system',
        'has_projector',
        'monitors',
        'tv_screens',
        'has_ethernet',
        'has_wifi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_podium' => 'boolean',
        'has_sound_system' => 'boolean',
        'has_projector' => 'boolean',
        'has_ethernet' => 'boolean',
        'has_wifi' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isAvailable($date, $startTime, $endTime, $excludeBookingId = null)
    {
        $query = $this->bookings()
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }

    public function getAmenitiesListAttribute()
    {
        $amenities = [];
        
        if ($this->seats > 0) {
            $amenities[] = "{$this->seats} seats";
        }
        if ($this->tables > 0) {
            $amenities[] = "{$this->tables} table(s)";
        }
        if ($this->ac_units > 0) {
            $amenities[] = "{$this->ac_units} AC unit(s)";
        }
        if ($this->washroom_facilities > 0) {
            $amenities[] = "Washroom facilities";
        }
        if ($this->has_podium) {
            $amenities[] = "Podium";
        }
        if ($this->has_sound_system) {
            $amenities[] = "Sound & Microphone system";
        }
        if ($this->has_projector) {
            $amenities[] = "Projector";
        }
        if ($this->monitors > 0) {
            $amenities[] = "{$this->monitors} monitor(s)";
        }
        if ($this->tv_screens > 0) {
            $amenities[] = "{$this->tv_screens} TV screen(s)";
        }
        if ($this->has_ethernet) {
            $amenities[] = "Ethernet cable";
        }
        if ($this->has_wifi) {
            $amenities[] = "WiFi";
        }

        return $amenities;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function getImageThumbnailUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function uploadImage($file)
    {
        if ($this->image) {
            // Delete old image
            Storage::disk('public')->delete($this->image);
        }

        $path = $file->store('meeting-rooms', 'public');
        $this->update(['image' => $path]);
        
        return $path;
    }

    public function deleteImage()
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
            $this->update(['image' => null]);
        }
    }
} 