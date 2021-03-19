<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class checkRequest
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
        $token = $request->header('KradWorks');
        if(Auth::check()){
            if($token != "XNndUUEA95BKwQ2blrLbYcU0IRCb5YQW"){
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }
       
        return $next($request);
    }
}
