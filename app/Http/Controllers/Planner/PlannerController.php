<?php

namespace App\Http\Controllers\Planner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\DayPlanAddRequest;
use App\Http\Requests\DayPlanCreateRequest;
use App\Http\Requests\PlannerCreateRequest;
use App\Models\Planner\DayPlan;
use App\Models\Planner\Meal;
use App\Models\Planner\Planner;

class PlannerController extends Controller
{
    // Util

    private function access_planner( $user, $planner ) {
        $home = $user->home;
        $access = true;
        if( $planner->user_id ) {
            if( $planner->user_id != $user->id ) {
                $access = false;
            }
        } else {
            if( $planner->home_id != $home->id ) {
                $access = false;
            }
        }
        return $access;
    }

    // Routes

    public function create_planner( PlannerCreateRequest $request ) {

        // Get Data
        $user = auth()->user();
        $home = $user->home;

        // Create the planner
        $planner = Planner::create([
            'name' => $request['name'],
            'visible' => $request['visible'],
        ]);

        // Determine the type of planner
        if( $request['type'] == 'home' ) {
            // Home Planner
            $planner->update([
                'home_id' => $home->id,
            ]);
        } else {
            // User Planner
            $planner->update([
                'user_id' => $user->id,
            ]);
        }

        // Return the planner
        return response($planner, 201); // Created
    }

    public function get_planners( Request $request ) {

        // Get Data
        $user = auth()->user();
        $home = $user->home;

        // Get Relevant Planners
        $planners = Planner::where('home_id', $home->id)->orWhere('user_id', $user->id)->get();

        return response($planners, 200); // OK
    }

    public function create_day_plan( DayPlanCreateRequest $request ) {

        // Get Data
        $user = auth()->user();

        // Create Plan
        $plan = DayPlan::create([
            'user_id' => $user->id,
        ]);

        // Find the planner
        $planner = Planner::where('id', $request['planner_id'])->first();

        // Check if user can access planner
        if( !$this->access_planner( $user, $planner ) ) {
            return response([
                'message' => 'User does not have permission to edit this planner',
            ], 403); // Forbidden
        }

        // Update the planner with DayPlan
        $dayId = $request['day'].'_id'; // e.g 'mon' = 'mon_id'
        $planner->update([
            $dayId => $plan->id,
        ]);

        return response($plan, 201); // Created

    }

    public function get_day_plan( Request $request, $planId, $day ) {

        $user = auth()->user();

        // Find the planner
        $planner = Planner::where('id', $planId)->first();

        // Check if planner exists
        if( !$planner ) {
            return response([
                'message' => 'That planner does not exist',
            ], 404); // Not Found
        }

        // Check if user can access planner
        if( !$this->access_planner( $user, $planner ) ) {
            return response([
                'message' => 'User does not have permission to edit this planner',
            ], 403); // Forbidden
        }

        // Get the DayPlan
        $dayId = $day.'_id'; // e.g 'mon' = 'mon_id'
        $planId = $planner->$dayId;

        if( !$planId ) {
            return response([
                'message' => 'Day Plan not Found',
            ], 404); // Not Found
        }

        $plan = DayPlan::where('id', $planId)->first();

        return response($plan, 200); // OK

    }

    public function add_meal( DayPlanAddRequest $request ) {

        // Get Data
        $user = auth()->user();
        $plan = DayPlan::where('id', $request['plan_id'])->first();
        $meal = Meal::where('id', $request['meal_id'])->first();

        // Find the associated planner
        $planner = Planner::where('mon_id', $plan->id)
                        ->orWhere('tues_id', $plan->id)
                        ->orWhere('wed_id', $plan->id)
                        ->orWhere('thurs_id', $plan->id)
                        ->orWhere('fri_id', $plan->id)
                        ->orWhere('sat_id', $plan->id)
                        ->orWhere('sun_id', $plan->id);

        // Check if user can access planner
        if( !$this->access_planner( $user, $planner ) ) {
            return response([
                'message' => 'User does not have permission to edit this planner',
            ], 403); // Forbidden
        }

        // Get the meal time
        $timeId = $request['time'].'_id';

        // Update the plan
        $plan->update([
            $timeId => $meal->id,
        ]);

        return response($plan, 200); // OK
    }
}
