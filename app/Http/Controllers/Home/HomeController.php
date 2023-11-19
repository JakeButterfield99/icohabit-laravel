<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\HomeCreateRequest;
use App\Http\Requests\HomeInviteRequest;
use App\Http\Requests\HomeGetInvitesRequest;
use App\Http\Requests\HomeJoinRequest;
use App\Http\Requests\HomeUpdateRequest;

use App\Models\Home\HomeInvite;
use App\Models\Auth\User;
use App\Models\Home\Home;
use App\Models\Home\InviteCode;

use DateTime;

class HomeController extends Controller
{
    // Util Functions
    private function UserHasHome($user){
        return isset($user->home_id);
    }

    public function create( HomeCreateRequest $request )
    {
        $user = auth()->user();

        // User has home, return error
        if ($this->UserHasHome($user)) {
            return response([
                'message' => 'User already has Home',
            ], 403); // Forbidden
        }

        // Create Home
        $home = Home::create([
            'name' => $request['name'],
            'address_line_1' => $request['address_line_1'],
            'address_line_2' => $request['address_line_2'],
            'address_city' => $request['address_city'],
            'address_county' => $request['address_county'],
            'address_postcode' => $request['address_postcode'],
        ]);

        // Update User
        $user->update([
            'home_id' => $home->id,
        ]);

        // Craft Response
        $response = [
            'home' => $home
        ];

        return response($response, 201); // Created
    }

    private function GenerateInviteCode() {
        $code = "";
        $valid = false;

        do {
            $code = strtoupper(substr(md5(mt_rand()), 0, 5));
            $valid = !InviteCode::where('code', $code)->exists();
        }
        while (!$valid);

        return $code;
    }

    public function get_invite_code( Request $request ) {
        $user = $request->user();

        $today = new DateTime();
        $expiryDate = new DateTime();
        date_add(
            $expiryDate,
            date_interval_create_from_date_string("1 day")
        );

        $inviteCode = InviteCode::where('user_id', $user->id)->first();

        // Code is valid
        if( $inviteCode != null ) {
            $t = $today->format('Y-m-d H:i:s');
            $e = $inviteCode->expires;

            if( $t > $e )
                $inviteCode->delete();
            else
                return response($inviteCode, 200);
        }
            

        $newCode = InviteCode::create([
            'user_id' => $user->id,
            'code' => $this->GenerateInviteCode(),
            'expires' => $expiryDate,
        ]);

        return response($newCode, 200);

    }

    public function invite( HomeInviteRequest $request )
    {
        // Get Data
        $user = auth()->user();
        $home = $user->home;
        $inviteCode = InviteCode::where('code', $request['code'])->first();

        // Check if code exists
        if( !$inviteCode ) {
            return response([
                'message' => 'That code was not found',
            ], 404); // Not Found
        }

        $inviteUser = User::where('id', $inviteCode->user_id)->first();

        // Check if user exists
        if( !$inviteUser ) {
            return response([
                'message' => 'That user was not found',
            ], 404); // Not Found
        }

        // Check if user has an invite already
        $match = ['home_id' => $home->id, 'user_id' => $inviteUser->id];
        $invite = HomeInvite::where($match)->first();

        if( $invite ) {
            return response([
                'message' => 'User has already been invited',
            ], 403); // Forbidden
        }

        // Create an Invite
        HomeInvite::create([
            'home_id' => $home->id,
            'user_id' => $inviteUser->id,
            'invited_by' => $user->id,
        ]);

        // Craft a response
        $response = [
            'message' => 'User was invited to the house',
        ];

        return response($response, 200); // OK
    }

    public function get_invites( Request $request )
    {
        $user = auth()->user();
        $invites = HomeInvite::where('user_id', $user->id)->get();
        
        return response($invites->toJson(), 200);
    }

    public function join( HomeJoinRequest $request )
    {

        // Get data
        $user = auth()->user();
        $home = Home::where('id', $request['home_id'])->first();

        // Home does not exists
        if( !$home ) {
            return response([
                'message' => 'That Home was not found',
            ], 404); // Not Found
        }

        // Check if user has an invite
        $match = ['home_id' => $home->id, 'user_id' => $user->id];
        $invite = HomeInvite::where($match)->first();

        // User not invited
        if( !$invite ) {
            return response([
                'message' => 'User has not been invited to this home',
            ], 403); // Forbidden
        }

        // User can join home
        $user->update([
            'home_id' => $home->id,
        ]);

        // Craft Response
        return response([
            'message' => 'User has joined the home',
            'home' => $home,
        ], 200); // OK

    }

    public function update( HomeUpdateRequest $request )
    {
        // Get data
        $user = auth()->user();
        $home = $user->home;

        // Update User
        $home->update([
            'home' => $request->name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'address_city' => $request->address_city,
            'address_county' => $request->address_county,
            'address_postcode' => $request->address_postcode,
        ]);

        // Craft Response
        return response([
            'message' => 'Home has been updated',
        ], 200); // OK

    }
}