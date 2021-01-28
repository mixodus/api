<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class UserCVController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	public function index(Request $request){

		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
        $getData = $this->getDataServices->employeeCV($checkUser->user_id);	
        
        return $this->services->response(200,"User CV",$getData);
	}
	public function create(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'file' => "required",
			'description' => "nullable|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
        }
        $file = $request->file('file');
		$imgname = $file->getClientOriginalName().'-'.round(microtime(true));
		$destinationPath = public_path('/uploads/user_cv/');
		$file->move($destinationPath,$imgname);
		
        $request['file_name'] = $imgname;
		$save = $this->actionServices->saveCV($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		return $this->services->response(200,"CV berhasil ditambahkan",$request->all());
	}
	public function delete(Request $request){
		$rules = [
			'employee_cv_id' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$save = $this->actionServices->deleteEmployeeCV($request->employee_cv_id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		return $this->services->response(200,"CV berhasil dihapus.",array());
	}
}
