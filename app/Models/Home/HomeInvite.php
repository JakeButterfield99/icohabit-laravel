<?php

namespace App\Models\Home;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Auth\User;
use App\Models\Home\Home;

class HomeInvite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_id',
        'user_id',
        'invited_by',
    ];

    protected $with = array(
        'inviteUser',
        'home'
    );

    public function inviteUser(){
        return $this->hasOne(User::class, 'id', 'invited_by');
    }

    public function home(){
        return $this->hasOne(Home::class, 'id', 'home_id');
    }
}
