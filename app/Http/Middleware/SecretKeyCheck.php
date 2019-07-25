<?php

namespace App\Http\Middleware;

use Closure;

class SecretKeyCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( env('SECRET_KEY') != $request->secret_key ){
            return response()->json([ 'error' => 1, 'message' => 'Invalid Secret Key' ]);
        }
        return $next($request);
    }
}
