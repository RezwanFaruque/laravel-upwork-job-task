<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()){
            if(Auth::user()->user_role == 'admin'){
                return $next($request);
            }
        }else{
            $data = [
                'status' => 'error',
                'message' => 'You are not an admin'
            ];

            return response()->json($data);
        }
       
    }
}
