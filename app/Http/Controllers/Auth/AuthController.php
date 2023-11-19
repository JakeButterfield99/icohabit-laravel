<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use DateTime;

class AuthController extends Controller
{

    public function register(RegisterRequest $request){

        // Create User
        $user = User::create([
            'email'         => $request['email'],
            'password'      => bcrypt($request['password']),
        ]);

        // Create Token
        $token = $user->createToken('apptoken')->plainTextToken;

        // Craft Response
        $response = [
            'user'  => $user,
            'token' => $token,
        ];

        return response($response, 201); // Created
    }

    public function login(LoginRequest $request){

        // Check Email
        $user = User::where('email', $request['email'])->first();

        // Check Password
        if( !$user || !Hash::check($request['password'], $user->password) ) {
            return response([
                'message' => 'Incorrect Credentials',
            ], 401); // Unauthorised
        }

        // Get Token
        $token = $user->createToken('apptoken')->plainTextToken;

        // Craft Response
        $response = [
            'user'  => $user,
            'home'  => $user->home,
            'token' => $token,
        ];

        return response($response, 200); // OK

    }

    public function logout(Request $request) {
        // Delete user's API tokens
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'Logged out',
        ];

        return response($response, 200); // OK
    }
}
