<?php

namespace App\Models\Shopping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shopping\ShoppingList;
use App\Models\Shopping\ListItem;

class ListEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'list_id',
        'item_id',
        'quantity',
        'checked',
    ];

    public function list(){
        return $this->hasOne(ShoppingList::class, 'id', 'list_id');
    }

    public function item(){
        return $this->hasOne(ListItem::class, 'id', 'item_id');
    }
}
