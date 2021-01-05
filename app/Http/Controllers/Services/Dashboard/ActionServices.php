<?php

namespace App\Http\Controllers\Services\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dashboard\PermissionsModel;
use App\Models\RolesModel;
use Symfony\Component\HttpFoundation\Response;

class ActionServices extends Controller
{
    public function getactionrole($role_id, $name_action, $data=null)
    {
        $getaction = PermissionsModel::select('xin_permissions.action')
                        ->join('xin_roles_permissions', 'xin_roles_permissions.permission_id', '=', 'xin_permissions.id')
                        ->where('xin_permissions.name', 'like','%'.$name_action.'%')
                        ->where('xin_roles_permissions.role_id', $role_id)
                        ->get()->toArray();
       
        foreach($getaction as $getactions){
            $data[]= $getactions['action'];
        }
        
        if(empty($data))
        {
            return response($data);
        }
        return response($data);
        
    }

    public function response($statusCode, $msg, $data=null,$with_alert= null){
		
		$response = [
			'status' => true,
			'message' => $msg,
			'data' => $data,
			'action' => $with_alert,
		];
		if ($statusCode != 200) {
			$response = [
				'status' => false,
				'message' => $msg
			];
		}
		if ($with_alert != null && $statusCode != 200) {
			$response = [
                'status' 	=> false,
                'alert' 	=> 'failed',
                'message' 	=> $msg
			];
			return response()->json($response, 200);
		}
		if ($with_alert != null && $statusCode != 200) {
			$response = [
                'status' 	=> true,
                'alert' 	=> 'success',
                'message' 	=> $msg
			];
		}
		return response()->json($response, $statusCode);
	}
}
