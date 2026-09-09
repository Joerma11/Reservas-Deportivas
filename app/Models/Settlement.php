<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = ['field_id', 'start_date', 'end_date', 'total_collected', 'commission', 'status'];

    public function field() { return $this->belongsTo(Field::class); }
}
