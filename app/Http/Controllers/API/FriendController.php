<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\GetDataServices;

class FriendController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->getDataServices = new GetDataServices();
	}
	public function index(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$response['data'] = $this->getDataServices->get_all_friends_complete($checkUser->user_id);
		
		return response()->json($response, 200);
	}
	public function add(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'to' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkStatus = $this->getDataServices->checkFriendStatus($request->to,$checkUser->user_id);
		if(!empty($checkStatus)){
			return $this->services->response(400,"User sudah menjadi teman / sedang menunggu persetujuan!");
		}
		$save = $this->actionServices->addFriend($request->to,$checkUser->user_id);
		if(!$save){
			return $this->services->response(400,"Server Error!");
		}
		return $this->services->response(200,"Permintaan pertemanan terkirim!", $save);        
	}
	public function approve(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'from' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->approve($request->from,$checkUser->user_id);
		if(!$save){
			return $this->services->response(400,"Server Error!");
		}
		return $this->services->response(200,"Permintaan pertemanan disetujui!", $save);        
	}
	public function unfriend(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'who' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->unFriend($request->who,$checkUser->user_id);
		
		return $this->services->response(200,"Pertemanan dibatalkan/dihapus!", $save);        
	}
	public function reject(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'who' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->reject($checkUser->user_id,$request->who);
		
		return $this->services->response(200,"Pertemanan ditolak!", $save);        
	}
}
