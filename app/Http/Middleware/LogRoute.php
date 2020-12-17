<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRoute
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
        $response = $next($request);

        $log = [
            'TYPE' =>  env("APP_VER", "Local"),
            'VER' =>  1,
            'IP_ADDRESS' =>  $request->getClientIp(),
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'REQUEST_BODY' => $request->all(),
            'RESPONSE' => $response->getContent(),
            'STATUS_CODE' => $response->getStatusCode()
        ];
        Log::info(json_encode($log));
     

        return $response;
    }
}
