<?php

namespace App\Models\Shopping;

use App\Models\Auth\User;
use App\Models\Shopping\ListItem;
use App\Models\Shopping\ShoppingList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListAction extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'list_id',
        'operation',
        'item_id',
        'item_name',
    ];

    public function list(){
        return $this->hasOne(ShoppingList::class, 'id', 'list_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function item(){
        return $this->hasOne(ListItem::class, 'id', 'item_id');
    }
}
