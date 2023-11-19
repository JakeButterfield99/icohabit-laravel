<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\CalendarCreateRequest;

use App\Models\Calendar\Calendar;

class CalendarController extends Controller
{
    public function create_calendar( CalendarCreateRequest $request ) {

        $user = auth()->user();
        $home = $user->home;

        $calendar = Calendar::create([
            'name' => $request['name'],
            'category' => $request['category'],
        ]);

        if( $request['type'] == 'home' ) {
            $calendar->update([
                'home_id' => $home->id,
            ]);
        } else {
            $calendar->update([
                'user_id' => $user->id,
            ]);
        }

        return response($calendar, 201); // Created

    }

    public function get_calendars( Request $request ) {

        $user = auth()->user();
        $home = $user->home;

        $calendars = Calendar::where('user_id', $user->id)->orWhere('home_id', $home->id)->get();

        return response($calendars, 200); // OK

    }
}
