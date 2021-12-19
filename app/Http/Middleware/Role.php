<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // if($user->isSuperAdmin())
        //     return $next($request);

        foreach($roles as $role) {
            if($user->user_level === $role){
                return $next($request);
            } 
        }
            return response(['msg' => "not authorized"]);
        
    }
}