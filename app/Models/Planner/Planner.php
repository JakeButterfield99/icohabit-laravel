<?php

namespace App\Models\Planner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mon_id',
        'tues_id',
        'wed_id',
        'thurs_id',
        'fri_id',
        'sat_id',
        'sun_id',
        'visible',
        'home_id',
        'user_id',
    ];
}
