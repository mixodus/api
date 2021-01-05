<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\GetDataServices;

class PeopleController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->getDataServices = new GetDataServices();
	}
	public function index(Request $request){

		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer",
			'q' => "nullable|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$getData = $this->getDataServices->userDatainArray(null,$request->q,$request->start,$request->length);	
		if (!$getData->isEmpty())
			$getData->makeHidden(['date_of_birth', 'gender', 'contact_no','address', 'marital_status', 'country', 'province','summary', 'zip_code','cash','points','skill_text']);

		return $this->services->response(200,"Daftar Jaringan",$getData);
	}
}
