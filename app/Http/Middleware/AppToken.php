<?php

namespace App\Http\Middleware;

use Closure;

class AppToken
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
        $header = $request->header('X-Api-Key');
        if($header != "idstar123!"){
            $response = [
                'status' 	=> false,
                'message' 	=> 'Unauthorized Access, invalid Api Key'
			];
            return response()->json($response, 401);
        }
        
        return $next($request);
    }
}
