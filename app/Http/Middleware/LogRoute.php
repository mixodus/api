<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Models\Fase2\LogModel;
use Illuminate\Http\Request;

class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$module=null,$name=null,$type=null)
    {
        $response = $next($request);
        $log = [
            'server_type' =>  env("SERVER_TYPE", "Local"),
            'module' =>  $module,
            'name' =>  $name,
            'type' =>  $type,
            'version' =>  env('APP_VERSION','0.00'),
            'user_id' =>  0,
            'ip_address' =>  $request->getClientIp(),
            'uri' => $request->getUri(),
            'method' => $request->getMethod(),
            'request_header' => $request->headers ,
            'request_body' => json_encode($request->all()),
            'response' => $response->getContent(),
            'status_code' => $response->getStatusCode()
        ];
        Log::info(json_encode($log));
        if($module != null){
            LogModel::create($log);
        }
        
        return $response;
    }
}
