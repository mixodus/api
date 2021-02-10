<?php

namespace App\Http\Controllers\API\Dashboard\MenuPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;

class LogController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
    }
    
    public function dashboardLog(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getLog = $this->getDataServices->dashboardLog();

        if ($getLog) {
            return $this->services->response(200,"Dashboard Log",$getLog);
        }else{
            return $this->services->response(404,"Dashboard log doesnt exist!");
        }
    }

    public function mobileLog(Request $request){
        
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getLog = $this->getDataServices->mobileLog();

        if ($getLog) {
            return $this->services->response(200,"Mobile Log",$getLog);
        }else{
            return $this->services->response(404,"Mobile log doesnt exist!");
        }
    }

    public function dashboardLogShow(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getLog = $this->getDataServices->dashboardLog($request->byDashboardLog);

        if (count($getLog) > 0) {
            return $this->services->response(200,"Dashboard Log Detail",$getLog);
        }else{
            return $this->services->response(404,"Dashboard log detail doesnt exist!");
        }
    }

    public function mobileLogShow(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getLog = $this->getDataServices->mobileLog($request->byMobileLog);

        if (count($getLog) > 0) {
            return $this->services->response(200,"Mobile Log detail",$getLog);
        }else{
            return $this->services->response(404,"Mobile log detail doesnt exist!");
        }
    }
}
