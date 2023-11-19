<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Home
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $home = $user->home;

        if( !$home ) {
            return response([
                'message' => 'User needs a valid home'
            ], 403); // Forbidden
        }

        return $next($request);
    }
}
