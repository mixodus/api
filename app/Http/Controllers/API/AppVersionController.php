<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;

class AppVersionController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
	}
    public function version(){
        $version['app-version'] = "20505";
        return $this->services->response(200, "App Version!", $version);
    }
}