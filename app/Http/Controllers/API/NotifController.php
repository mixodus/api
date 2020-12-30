<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class NotifController extends Controller
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
        $getData = $this->getDataServices->getNotif($checkUser->user_id);
		$data = [
			'totalLength' => count($getData),
			'data'      => $getData
		];
		return $this->services->response(200,"Notifikasi",$data);
	}
	public function detail(Request $request,$id){
		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		
		$checkUser = $this->getDataServices->getUserbyToken($request);
        $getData = $this->getDataServices->getNotif($checkUser->user_id,$id);
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Notifikasi Detail",$getData);
		}else{
			return $this->services->response(200,"Data tidak ditemukan!",null);
		}
	}
	public function newNotif(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->getNotif($checkUser->user_id);
		
		$getData = $this->getDataServices->getNotif($checkUser->user_id,null,1);
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Jumlah Notifikasi",count($getData));
		}else{
			return $this->services->response(200,"Data tidak ditemuka!",null);
		}
	}
	public function update(Request $request){

		$rules = [
			'notif_id' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$data['is_new'] = 1;
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$updateNotif = $this->actionServices->updateNotif($data,$request->notif_id,$checkUser->user_id);
		if(!$updateNotif){
			return $this->services->response(400,"Server Error!");
        }
		return $this->services->response(200,"Notif!", $updateNotif);
	}
}
