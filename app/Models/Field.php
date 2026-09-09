<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'sport_type', 'price_per_hour', 'is_active'];
    public function schedules() { return $this->hasMany(Schedule::class); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function blockedSchedules() { return $this->hasMany(BlockedSchedule::class); }
    public function settlements() { return $this->hasMany(Settlement::class); }
}
