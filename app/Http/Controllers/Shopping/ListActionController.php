<?php

namespace App\Http\Controllers\Shopping;

use App\Http\Requests\ShoppingListActionRequest;
use App\Models\Shopping\ListAction;
use App\Models\Shopping\ListEntry;
use App\Models\Shopping\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Shopping\ShoppingListController;
use App\Http\Traits\UtilTrait;

class ListActionController extends Controller
{
    use UtilTrait;

    public function __construct(Request $request)
    {
        $this->shopController = new ShoppingListController();
    }

    public function get_actions( Request $request, string $last_check ) {
        $user = auth()->user();
        $home = $user->home;
        $actions = array();
        $lists = ShoppingList::where('home_id', $home->id)
            ->orWhere('user_id', $user->id)->get();

        if(!$last_check) {
            return response([
                'message' => 'Missing last_check unix time',
            ], 404); // Not Found
        }

        foreach( $lists as $list ) {
            $list_actions = $list->actions()
                ->where('created_at', '>', $last_check)
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
            $actions = array_merge($actions, $list_actions);
        }

        return response($actions, 200); // ok
    }

    public function get_list_actions( Request $request, int $list_id ) {
        $user = auth()->user();
        $list = ShoppingList::where('id', $list_id)->first();

        if( !$this->access_model( $user, $list ) )
            return;

        $actions = $list->actions()
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
        return response($actions, 200); // ok
    }

    private function process_action( ListAction $action ) {
        $item_id = $action->item_id;
        $operation = $action->operation;

        if(!$item_id) {
            info("NO ITEM FOUND");
            // to do: create item and return it
            return;
        }

        switch ($operation) {
            case 'add':
                $this->shopController->add_item($action->item_id, $action->list_id);
                break;
        }
    }

    public function create_action( ShoppingListActionRequest $request ) {
        $user = auth()->user();
        $list_id = $request->input('list_id');
        $item_id = $request->input('item_id');
        $item_name = $request->input('item_name');
        $operation = $request->input('operation');

        if(!$item_id || $item_name) {
            return response([
                'message' => 'Must provide item_id or item_name',
            ], 404); // Not Found
        };

        $action = ListAction::create([
            'user_id' => $user->id,
            'list_id' => $list_id,
            'operation' => $operation,
            'item_id' => $item_id,
        ]);

        $this->process_action($action);

        return response($action, 200); // ok
    }
}
