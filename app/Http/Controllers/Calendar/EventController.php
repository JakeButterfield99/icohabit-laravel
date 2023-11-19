<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventEditRequest;
use App\Http\Requests\EventJoinRequest;
use App\Http\Requests\EventLeaveRequest;

use App\Http\Traits\UtilTrait;

use App\Models\Calendar\Calendar;
use App\Models\Calendar\Event;
use App\Models\Calendar\EventJoin;

class EventController extends Controller
{
    use UtilTrait;

    public function get_events( Request $request, $calendar_id ) {

        $user = auth()->user();

        $calendar = Calendar::where( 'id', $calendar_id )->first();

        if( !$calendar ) {
            return response([
                'message' => 'Could not find Calendar',
            ], 404); // Not Found
        }

        if( !$this->access_model( $user, $calendar ) ) {
            return response([
                'message' => 'User cannot access this calendar',
            ], 403); // Forbidden
        }

        $events = $calendar->events;

        return response($events, 200); // OK

    }

    public function get_joins( Request $request, $event_id ) {

        $user = auth()->user();
        $event = Event::where('id', $event_id)->first();

        if( !$event ) {
            return response([
                'message' => 'Event cannot be found',
            ], 404); // Not Found
        }

        $calendar = $event->calendar;

        if( !$this->access_model( $user, $calendar ) ) {
            return response([
                'message' => 'User cannot access this calendar',
            ], 403); // Forbidden
        }

        $joins = EventJoin::where('event_id', $event_id)->get();

        return response($joins, 200); // OK

    }

    public function create_event( EventCreateRequest $request ) {

        $user = auth()->user();
        $home = $user->home;

        $calendar = Calendar::where( 'id', $request['calendar_id'] )->first();

        if( !$this->access_model( $user, $calendar ) ) {
            return response([
                'message' => 'User cannot access this calendar',
            ], 403); // Forbidden
        }

        $event = [
            'calendar_id' => $request['calendar_id'],
            'name' => $request['name'],
            'datetime' => $request['datetime'],
        ];

        // Update type
        if( $request['type'] == 'home' )
            $event['home_id'] = $home->id;
        else
            $event['user_id'] = $user->id;

        // Process Addresses
        if( isset($request['location_line_1']) )
            $event['location_line_1'] = $request['location_line_1'];

        if( isset($request['location_line_2']) )
            $event['location_line_2'] = $request['location_line_2'];

        if( isset($request['location_city']) )
            $event['location_city'] = $request['location_city'];

        if( isset($request['location_county']) )
            $event['location_county'] = $request['location_county'];

        if( isset($request['location_postcode']) )
            $event['location_postcode'] = $request['location_postcode'];

        // Create Event
        $newEvent = Event::create( $event );

        return response($newEvent, 201); // Created

    }

    public function join_event( EventJoinRequest $request ) {

        $user = auth()->user();
        $event = Event::where('id', $request['event_id'])->first();
        $calendar = $event->calendar;

        if( !$this->access_model( $user, $calendar ) ) {
            return response([
                'message' => 'User cannot access this calendar',
            ], 403); // Forbidden
        }

        $eventJoin = EventJoin::create([
            'event_id' => $request['event_id'],
            'user_id' => $user->id,
        ]);

        return response($eventJoin, 201); // Created

    }

    public function edit_event( EventEditRequest $request ) {

        $user = auth()->user();
        $event = Event::where('id', $request['event_id'])->first();
        $calendar = $event->calendar;

        if( !$this->access_model( $user, $calendar ) ) {
            return response([
                'message' => 'User cannot access this calendar',
            ], 403); // Forbidden
        }

        $data = json_decode( $request['data'], true );

        $event->update($data);

        return response($event, 200); // OK

    }

    public function leave_event( EventLeaveRequest $request ) {

        $user = auth()->user();
        $event = Event::where('id', $request['event_id'])->first();
        $calendar = $event->calendar;

        if( !$this->access_model( $user, $calendar ) ) {
            return response([
                'message' => 'User cannot access this calendar',
            ], 403); // Forbidden
        }

        $join = EventJoin::where('id', $request['event_id'])->first();

        if( isset($join) )
            $join->delete();

        return response([
            'message' => 'User has left the event'
        ],200); // OK

    }
}
