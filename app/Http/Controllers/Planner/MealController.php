<?php

namespace App\Http\Controllers\Planner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\IngredientCreateRequest;
use App\Http\Requests\MealAddRequest;
use App\Http\Requests\MealCreateRequest;
use App\Http\Requests\MealEditRequest;
use App\Models\Planner\Ingredient;
use App\Models\Planner\Meal;
use App\Models\Planner\MealIngredient;

class MealController extends Controller
{
    public function create_meal( MealCreateRequest $request ) {

        // Get Data
        $user = auth()->user();

        // Create Meal
        $meal = Meal::create([
            'name' => $request['name'],
            'time' => $request['time'],
            'method' => $request['method'],
            'visible' => $request['visible'],
            'user_id' => $user->id,
        ]);

        return response($meal, 201); // Created

    }

    public function get_meals( Request $request ) {

        $user = auth()->user();

        $match = ['user_id' => $user->id, 'visible' => 'private'];
        $meals = Meal::where($match)->orWhere('visible', 'public')->get();

        return response($meals, 200); // OK

    }

    public function edit_meal( MealEditRequest $request ) {

        $user = auth()->user();
        $meal = Meal::where('id', $request['meal_id'])->first();

        // Check if user can edit meal
        if( $meal->user_id != $user->id ) {
            return response([
                'message' => 'You do not have permission to edit this meal',
            ], 403); // Forbidden
        }

        // Edit Meal
        $updates = json_decode($request['data'], true);
        $meal->update( $updates );

        return response($meal, 200); // OK

    }

    public function get_meal_ingredients( Request $request, $meal_id ) {

        $meal = Meal::where('id', $meal_id)->first();

        // Can't find the meal
        if( !$meal ) {
            return response([
                'message' => 'Meal not found',
            ], 404); // Not Found
        }

        // Retrieve the Ingredients
        $ingredients = MealIngredient::where('meal_id', $meal->id)->get();

        return response($ingredients, 200); // OK

    }

    public function create_ingredient( IngredientCreateRequest $request ) {

        // Get Data
        $user = auth()->user();

        // Create Ingredient
        $ingredient = Ingredient::create([
            'name' => $request['name'],
            'category' => $request['category'],
            'user_id' => $user->id,
        ]);

        return response($ingredient, 201); // Created

    }

    public function get_ingredients( Request $request ) {

        $ingredients = Ingredient::all();
        return response($ingredients, 200); // OK

    }

    public function add_ingredient( MealAddRequest $request ) {

        $user = auth()->user();
        $meal = Meal::where('id', $request['meal_id'])->first();

        // Check if user can edit meal
        if( $meal->user_id != $user->id ) {
            return response([
                'message' => 'You do not have permission to edit this meal',
            ], 403); // Forbidden
        }

        // Create the ingredient
        $mealIngredient = MealIngredient::create([
            'meal_id' => $request['meal_id'],
            'ingredient_id' => $request['ingredient_id'],
            'amount' => $request['amount'],
            'unit' => $request['unit'],
        ]);

        return response($mealIngredient, 201); // Created

    }
}
