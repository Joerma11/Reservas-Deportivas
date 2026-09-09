<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'field_id', 'date', 'start_time', 'end_time', 'total_price', 'status', 'qr_code_path'];

    public function user() { return $this->belongsTo(User::class); }
    public function field() { return $this->belongsTo(Field::class); }
}
