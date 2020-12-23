<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\ReferenceModel;

class GeneralController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	
	public function getCountryList(Request $request){

		$getCountry = $this->getDataServices->getCountryList();	

		return $this->services->response(200,"Daftar Negara",$getCountry);
	}
	public function generalSearch(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'q' => "nullable"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$job_data = $this->getDataServices->getJobs(null,null,$request['q']);
		$employee_data = $this->getDataServices->searchuserData($checkUser->user_id, $request['q']);
		$arr1 = array('size' => count($employee_data), 'data' => $employee_data);
		$arr2 = array('size' => count($job_data), 'data' => $job_data);

		$searchResult = array_merge($arr1,$arr2);
		return $this->services->response(200,"Hasil Pemcarian General",$searchResult);
	}
	public function referenceSeacrh(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'q' => "nullable",
			'cat' => "nullable"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$searchResult = ReferenceModel::select('*')->where('name','LIKE','%'.$request->q.'%')->where('category','LIKE','%'.$request->cat.'%')->get();
		return $this->services->response(200,"Hasil Pencarian Referensi",$searchResult);
	}
	public function site(){
        $data['title'] = "One Talent";
        return view('general.sites', $data);
    }
}
