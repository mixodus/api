<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Http\Controllers\Services\ActionServices;

class ConnectionController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->getDataServices = new GetDataServices();
		$this->actionServices = new ActionServices();
	}
	//listing connection
	public function getConnected(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->getConnected($checkUser->user_id, $request->page);
		return $this->services->response(200,"Connected connections!" ,$data);
	}
	public function discover(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->get_all_connection($checkUser->user_id, $request->page);
		return $this->services->response(200,"Discover connections!" ,$data);
	}
	//connection on action
	public function requestConnection(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'to' => "required",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$checkStatus = $this->getDataServices->checkConnectionStatus($request->to,$checkUser->user_id);
		if(!empty($checkStatus)){
			return $this->services->response(400,"User yang anda tambahkan sedang menunggu persetujuan!");
		}
		$save = $this->actionServices->addConnection($request->to,$checkUser->user_id);
		if(!$save){
			return $this->services->response(400,"User yang anda tambahkan tidak tersedia / Server Error!");
		}
		return $this->services->response(200,"Permintaan pertemanan terkirim!", $save);        
	}
	public function cancelConnectionRequest(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'to' => "required",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$data = $this->actionServices->cancelConnectionRequest($request->to,$checkUser->user_id);
		if($data==false){
			return $this->services->response(406,"Data tidak ditemukan!", $data);        
		}
		return $this->services->response(200,"Pembatalaan permintaan pertemanan berhasil!", $data);        
	}
	public function acceptConnectionRequest(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'from' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$save = $this->actionServices->acceptConnection($request->from,$checkUser->user_id);
		if(!$save){
			return $this->services->response(400,"Sudah menjadi teman! / Server Error!");
		}
		return $this->services->response(200,"Permintaan pertemanan disetujui!");        
	}
	public function unconnect(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'who' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->unconnectConnection($request->who,$checkUser->user_id);
		
		return $this->services->response(200,"Pertemanan berhasil dihapus!");        
	}
	public function rejectConnection(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'who' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$this->actionServices->rejectConnection($checkUser->user_id,$request->who);
		
		return $this->services->response(200,"Pertemanan berhasil ditolak!");        
	}

	//listing section
	public function requestsList(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		
		$data = $this->getDataServices->requests($checkUser->user_id);
		
		return $this->services->response(200,"Daftar Permintaan Pertemanan!", $data);        
	}
	public function friendRequestId(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);

		$data = $this->getDataServices->friendRequestList($checkUser->user_id);
		$return = array();
		if(!empty($data)){
			foreach($data as $key){
				$array['user_id'] = $key->user_id;
				$return[] = $array;
			}
		}
		return $this->services->response(200,"Daftar ID User Permintaan Pertemanan!", $return);    
	}
	
	public function remove_element($element, $theList){
		$result = [];

		if (sizeof($theList) == 0){
			$result = [];
		}
		else {
			$result = $theList;
			if (($key = array_search($element, $result)) !== false) {
				unset($result[$key]);
			}
		}
		return $result;
	}
}
