<?php

namespace App\Models\IOU;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IOU extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'requestor_id',
        'debtor_id',
        'amount',
        'description',
        'due_by',
        'paid',
    ];
}
