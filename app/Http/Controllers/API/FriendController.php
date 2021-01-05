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
}
