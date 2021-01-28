<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use App\Models\Dashboard\LogActivity;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use Illuminate\Http\Request;

use Closure;

class LogDashboard
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
        $this->getDataServices = new GetDataServices();
        $user = $this->getDataServices->getAdminbyToken($request);

        $log = [
            'server_type' =>  env("SERVER_TYPE", "Local"),
            'type' =>  $type,
            'module' =>  $module,
            'name' =>  $name,
            'uri' => $request->getUri(),
            'user_id' =>  $user->user_id,
            'ip_address' =>  $request->getClientIp(),
            'method' => $request->getMethod(),
            'request_header' => $request->header('user-agent'),
            'request_body' => json_encode($request->all()),
            'response' => $response->getContent(),
            'status_code' => $response->getStatusCode()
        ];
        
        // Log::info(json_encode($log));
        LogActivity::create($log);
        
        return $response;
    }
}
