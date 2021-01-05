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
	//listing friend
	public function index(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$response['data'] = $this->getDataServices->get_all_friends_complete($checkUser->user_id);
		
		return response()->json($response, 200);
	}
	public function listingId(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->get_all_friends_complete($checkUser->user_id);
		
		$return = array();
		if(!empty($data)){
			foreach($data as $key){
				$array['user_id'] = $key->user_id;
				$return[] = $array;
			}
		}
		$response['data'] = $return;
		return response()->json($response, 200);
	}
	//mutual listing
	public function mutual(Request $request){
		$rules = ['id' => "required|integer"];
		$checkValidate = $this->services->validate($request->all(),$rules);

		$checkUser = $this->getDataServices->getUserbyToken($request);

		$data = $this->getDataServices->get_all_friends_complete($request->id);
		
		$list2 = array();
		if(count($data)>0){
			foreach($data as $key){
				$array = $key->user_id;
				$list2[] = $array;
			}
		}
		$res = array();
		if(count($list2)>0){
			$res = $this->getDataServices->userDatainArray($list2);
		}

		$response['count'] = sizeof($res);
		$response['data'] = $res;
		
		return response()->json($response, 200);
	}
	public function mutualId(Request $request){
		$rules = ['id' => "required|integer"];
		$checkValidate = $this->services->validate($request->all(),$rules);

		$checkUser = $this->getDataServices->getUserbyToken($request);

		$data = $this->getDataServices->get_all_friends_complete($request->id);
		
		$list2 = array();
		if(count($data)>0){
			foreach($data as $key){
				$array = $key->user_id;
				$list2[] = $array;
			}
		}
		$res = array();
		if(count($list2)>0){
			$res = $this->getDataServices->userIDinArray($list2);
		}

		$response['count'] = sizeof($res);
		$response['data'] = $res;
		
		return response()->json($response, 200);
	}
	//friend on action
	public function add(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = ['to' => "required"];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
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
		$rules = ['from' => "required"];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
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

	//listing section
	public function friendRequestList(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);

		$data = $this->getDataServices->friendRequestList($checkUser->user_id);
		
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
