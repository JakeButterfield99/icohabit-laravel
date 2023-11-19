<?php

namespace App\Models\Pinboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pinboard_id',
        'category_id',
        'content',
        'date',
        'anon',
        'user_id',
    ];
}
