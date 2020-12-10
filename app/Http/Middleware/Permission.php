<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Services\GetDataServices;

class Permission
{
    public function __construct()
    {
        $this->getDataServices = new GetDataServices();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $action)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        $actions  = explode('|', $action);
        
        if ($checkUser) {
            if ($request->header('Access-From') == "service"){
                return $next($request);
            }
            foreach ($actions as $item) {
                $permission = \DB::table("xin_permissions")->where("name", $item)->first();
                if (!$permission){
                    $return_value =
                        array(
                            'status' => false,
                            'message' => 'You dont have permission to access this page.'
                        );
                    return response()->json($return_value, 403);
                }
            }
            
            $check = \DB::table("xin_roles_permissions")->where("role_id", $checkUser->role_id)
                ->where("permission_id", $permission->id)
                ->first();
            
            if ($check){
                return $next($request);
            }else{
                $return_value =
                    array(
                        'status' => false,
                        'message' => 'You dont have permission to access this page.'
                    );
                return response()->json($return_value, 403);
            }

        }

        return $next($request);
    }
}
