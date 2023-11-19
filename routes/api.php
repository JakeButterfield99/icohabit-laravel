<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Shopping\ShoppingListController;
use App\Http\Controllers\Planner\PlannerController;
use App\Http\Controllers\Planner\MealController;
use App\Http\Controllers\Calendar\CalendarController;
use App\Http\Controllers\Calendar\EventController;
use App\Http\Controllers\Rota\RotaController;
use App\Http\Controllers\Shopping\ListActionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 *  Auth Routes
 */

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
 *  Login Required
 */
Route::group(['middleware' => ['auth:sanctum']], function(){

    // Auth Routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Home Routes
    Route::group(['prefix' => 'home'], function(){

        // No Home Required
        Route::post('create', [HomeController::class, 'create']);
        Route::post('join', [HomeController::class, 'join']);

        Route::get('invite/code', [HomeController::class, 'get_invite_code']);
        Route::get('invites', [HomeController::class, 'get_invites']);

        // Home is Required
        Route::group(['middleware' => ['home']], function(){

            Route::get('/', function (Request $request) {
                return $request->user()->home;
            });

            Route::get('users', function(Request $request) {
                return $request->user()->home->users;
            });

            Route::patch('update', [HomeController::class, 'update']);
            Route::post('invite', [HomeController::class, 'invite']);
        });

    });

    // Shopping Routes  [Home Required]
    Route::group(['prefix' => 'shopping', 'middleware' => ['home']], function(){

        Route::get('list/get/{last_refresh}', [ShoppingListController::class, 'refresh_lists']);
        Route::post('list/create', [ShoppingListController::class, 'create_list']);

        Route::get('list/action/get/{last_check}', [ListActionController::class, 'get_actions']);
        Route::get('list/{list_id}/actions', [ListActionController::class, 'get_list_actions']);
        Route::post('list/action/create', [ListActionController::class, 'create_action']);

        Route::get('list/{list_id}', [ShoppingListController::class, 'list_items']);

        Route::get('item/get/{last_refresh}', [ShoppingListController::class, 'refresh_items']);
        Route::post('item/create', [ShoppingListController::class, 'create_item']);

    });

    // Planner Routes  [Home Required]
    Route::group(['prefix' => 'planner', 'middleware' => ['home']], function(){

        // Meal Planner
        Route::post('create', [PlannerController::class, 'create_planner']);
        Route::get('get', [PlannerController::class, 'get_planners']);

        // Day Plan
        Route::post('day/create', [PlannerController::class, 'create_day_plan']);
        Route::get('day/get/{plan_id}/{day}', [PlannerController::class, 'get_day_plan']);
        Route::post('day/add', [PlannerController::class, 'add_meal']);

        // Meals
        Route::post('meal/create', [MealController::class, 'create_meal']);
        Route::get('meal/get', [MealController::class, 'get_meals']);
        Route::patch('meal/edit', [MealController::class, 'edit_meal']);
        Route::get('meal/ingredients/{meal_id}', [MealController::class, 'get_meal_ingredients']);

        // Ingredients
        Route::post('ingredient/create', [MealController::class, 'create_ingredient']);
        Route::get('ingredient/get', [MealController::class, 'get_ingredients']);
        Route::post('ingredient/add', [MealController::class, 'add_ingredient']);
    });

    // Calendar Routes  [Home Required]
    Route::group(['prefix' => 'calendar', 'middleware' => ['home']], function() {

        Route::post('create', [CalendarController::class, 'create_calendar']);
        Route::get('get', [CalendarController::class, 'get_calendars']);
        Route::get('events/{calendar_id}', [EventController::class, 'get_events']);
        Route::get('joins/{event_id}', [EventController::class, 'get_joins']);

        Route::post('event/create', [EventController::class, 'create_event']);
        Route::post('event/join', [EventController::class, 'join_event']);
        Route::patch('event/edit', [EventController::class, 'edit_event']);
        Route::post('event/leave', [EventController::class, 'leave_event']);

    });

    // Jobs Rota Routes  [Home Required]
    Route::group(['prefix' => 'rota', 'middleware' => ['home']], function() {

        Route::post('create', [RotaController::class, 'create_rota']);
        Route::get('get', [RotaController::class, 'get_rotas']);

        Route::get('task/get', [RotaController::class, 'get_tasks']);
        Route::post('task/create', [RotaController::class, 'create_task']);
        Route::post('task/add', [RotaController::class, 'add_task']);
        Route::post('task/join', [RotaController::class, 'join_task']);
        Route::post('task/assign', [RotaController::class, 'assign_task']);

        Route::get('task/{rota_id}', [RotaController::class, 'get_rota_tasks']);

    });

});
