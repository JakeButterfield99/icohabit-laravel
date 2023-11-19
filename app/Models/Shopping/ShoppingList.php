<?php

namespace App\Models\Shopping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Auth\User;
use App\Models\Home\Home;
use App\Models\Shopping\ListAction;

class ShoppingList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'private',
        'home_id',
        'user_id',
    ];

    public function home(){
        return $this->hasOne(Home::class, 'id', 'home_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function actions() {
        return $this->hasMany(ListAction::class, 'list_id', 'id');
    }
}
