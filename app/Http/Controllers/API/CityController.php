<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class CityController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
    public function getCityByCountry(Request $request){
        $data = $this->getDataServices->CityByCountry($request);
        if(!empty($data))
            return $this->services->response(202, "City List", $data);
        return $this->services->response(406, "City Not Found");
    }
}
