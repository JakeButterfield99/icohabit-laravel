<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'calendar_id',
        'name',
        'datetime',
        'location_line_1',
        'location_line_2',
        'location_city',
        'location_county',
        'location_postcode',
        'home_id',
        'user_id',
    ];

    public function calendar() {
        return $this->belongsTo(Calendar::class);
    }
}
