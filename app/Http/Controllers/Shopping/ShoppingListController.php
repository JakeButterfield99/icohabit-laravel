<?php

namespace App\Http\Controllers\Shopping;

use App\Http\Controllers\Controller;

use App\Http\Requests\ShoppingItemCreateRequest;
use App\Http\Requests\ShoppingListAddRequest;
use App\Http\Requests\ShoppingListRemoveRequest;
use App\Http\Requests\ShoppingListMinusRequest;
use App\Http\Requests\ShoppingListPlusRequest;
use App\Http\Requests\ShoppingListCheckRequest;
use App\Http\Requests\ShoppingListCreateRequest;

use App\Http\Traits\UtilTrait;
use App\Models\Shopping\ListEntry;
use App\Models\Shopping\ListItem;
use App\Models\Shopping\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Controllers\UtilController;

class ShoppingListController extends Controller
{
    use UtilTrait;

    // Routes

    public function refresh_lists( Request $request, string $lastRefresh ) {

        $user = auth()->user();
        $home = $user->home;

        $lists = ShoppingList::where('home_id', $home->id)
                    ->orWhere('user_id', $user->id)->get();
        $lengths = [];

        $lists = $lists->where('updated_at', '>', $lastRefresh);
        foreach( $lists as $list ) {
            $entry = ListEntry::where('list_id', $list->id)->count();
            $lengths[$list->id] = $entry;
        }

        $response = [
            'lists' => $lists,
            'lengths' => $lengths,
        ];

        return response($response, 200); // OK

    }

    public function create_list( ShoppingListCreateRequest $request ) {

        $user = auth()->user();
        $home = $user->home;

        $list = ShoppingList::create([
            'name' => $request['name'],
            'category' => $request['category'],
        ]);

        if( $request['private'] ) {
            $list->update([
                'user_id' => $user->id,
            ]);
        } else {
            $list->update([
                'home_id' => $home->id,
            ]);
        }

        // Craft a Response
        $response = [
            'message' => 'Shopping List Created',
            'list_id' => $list->id,
        ];

        return response($response, 201); // Created

    }

    public function refresh_items( Request $request, string $lastRefresh ) {
        $items = ListItem::where('updated_at', '>', $lastRefresh)->get();
        return response($items, 200); // OK
    }

    public function create_item( ShoppingItemCreateRequest $request )
    {
        $user = auth()->user();

        $item = ListItem::create([
            'name' => $request['name'],
            'category' => $request['category'],
            'visible' => $request['visible'],
            'user_id' => $user->id,
        ]);

        $response = [
            'message' => 'Item Created',
            'item_id' => $item->id,
        ];

        return response($response, 201); // Created
    }

    public function add_item( $item_id, $list_id ) {
        $user = auth()->user();
        $list = ShoppingList::where('id', $list_id)->first();

        // Check if user can access list
        if( !$this->access_model( $user, $list ) ) {
            return;
        }

        $entry = ListEntry::where('list_id', $list_id)
            ->where('item_id', $item_id)
            ->first();

        if($entry) {
            $entry->quantity = $entry->quantity + 1;
            $entry->update();
        }
        else {
            // Create List Entry
            $entry = ListEntry::create([
                'list_id' => $list->id,
                'item_id' => $item_id,
            ]);
        }
    }

    public function remove_item( ShoppingListRemoveRequest $request ) {
        $user = auth()->user();
        $home = $user->home;

        $list = ShoppingList::where('id', $request['list_id'])->first();

        // Check if user can access list
        $access = $this->access_model( $user, $list );

        // User cannot access list
        if( !$access ) {
            return response([
                'message' => 'User cannot access this list',
            ], 403); // Forbidden
        }

        // Get List Entry
        $entry = ListEntry::where([
            'item_id' => $request->input('item_id'),
            'list_id' => $request->input('list_id'),
        ])->first();

        if(!$entry) return response(404);

        // Delete List Entry
        $entry->delete();

        $response = [
            'message' => 'Item removed from list',
        ];

        return response($response, 204); // Removed
    }

    public function minus_item( ShoppingListMinusRequest $request ) {
        $user = auth()->user();
        $home = $user->home;

        $list = ShoppingList::where('id', $request['list_id'])->first();

        // Check if user can access list
        $access = $this->access_model( $user, $list );

        // User cannot access list
        if( !$access ) {
            return response([
                'message' => 'User cannot access this list',
            ], 403); // Forbidden
        }

        // Get List Entry
        $entry = ListEntry::where([
            'item_id' => $request->input('item_id'),
            'list_id' => $request->input('list_id'),
        ])->first();

        if(!$entry) return response(404);

        // Update List Entry
        $newQuantity = max($entry->quantity - 1, 1);
        $entry->update([
            'quantity' => $newQuantity,
        ]);

        $response = [
            'message' => 'Item quantity decreased',
        ];

        return response($response, 204); // Updated
    }

    public function plus_item( ShoppingListPlusRequest $request ) {
        $user = auth()->user();
        $home = $user->home;

        $list = ShoppingList::where('id', $request['list_id'])->first();

        // Check if user can access list
        $access = $this->access_model( $user, $list );

        // User cannot access list
        if( !$access ) {
            return response([
                'message' => 'User cannot access this list',
            ], 403); // Forbidden
        }

        // Get List Entry
        $entry = ListEntry::where([
            'item_id' => $request->input('item_id'),
            'list_id' => $request->input('list_id'),
        ])->first();

        if(!$entry) return response(404);

        // Update List Entry
        $newQuantity = min($entry->quantity + 1, 99);
        $entry->update([
            'quantity' => $newQuantity,
        ]);

        $response = [
            'message' => 'Item quantity decreased',
        ];

        return response($response, 204); // Updated
    }

    public function check_item( ShoppingListCheckRequest $request ) {
        $user = auth()->user();
        $home = $user->home;

        $list = ShoppingList::where('id', $request['list_id'])->first();

        // Check if user can access list
        $access = $this->access_model( $user, $list );

        // User cannot access list
        if( !$access ) {
            return response([
                'message' => 'User cannot access this list',
            ], 403); // Forbidden
        }

        // Get List Entry
        $entry = ListEntry::where([
            'item_id' => $request->input('item_id'),
            'list_id' => $request->input('list_id'),
        ])->first();

        if(!$entry) return response(404);

        // Update List Entry
        $checked = $entry->checked;
        $entry->update([
            'checked' => !$checked,
        ]);

        $response = [
            'message' => 'Item quantity decreased',
        ];

        return response($response, 204); // Updated
    }

    public function list_items( Request $request, $list_id ) {

        $user = auth()->user();
        $list = ShoppingList::where('id', $list_id)->first();

        if(!$list) {
            return response([
                'message' => 'List Not Found',
            ], 404); // Not Found
        }

        // Check if user can access list
        $access = $this->access_model( $user, $list );

        // User cannot access list
        if( !$access ) {
            return response([
                'message' => 'User cannot access this list',
            ], 403); // Forbidden
        }

        $entries = ListEntry::where('list_id', $list_id)->get();

        return response($entries, 200); // OK

    }
}
