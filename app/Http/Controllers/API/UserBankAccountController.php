<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class UserBankAccountController extends Controller
{
	private $activity_point;

	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	public function index(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->getUserBankAccount($checkUser->user_id);	

		return $this->services->response(200,"Akun Bank List",$getData);
	}
	public function add(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'account_name' => "required|string",
			'account_number' => "required",
			'is_primary' => "required|integer",
			'bank_id' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->saveUserBankAccount($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		
		return $this->services->response(200,"Bank Akun berhasil ditambahkan",$request->all());
	}
	public function update(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'account_list_id' => "required",
			'account_name' => "required|string",
			'account_number' => "required",
			'is_primary' => "required|integer",
			'bank_id' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->updateUserBankAccount($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Terjadi kesalahan jaringan!");
		} 
		return $this->services->response(200,"Akun berhasil di update.",$request->all());
	}
	public function delete(Request $request){
		$rules = [
			'id' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$save = $this->actionServices->deleteUserBankAccount($request->id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		return $this->services->response(200,"Bank Akun berhasil dihapus.",array());
	}
}
