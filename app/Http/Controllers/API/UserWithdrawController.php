<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class UserWithdrawController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	
	public function index(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->getWithdrawInfo($checkUser->user_id);	

		return $this->services->response(200,"Withdraw Statistics",$getData);
	}
	public function history(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData['data'] = $this->getDataServices->getWithdrawHistory($checkUser->user_id);	

		return $this->services->response(200,"Withdraw History",$getData);
	}
	public function check(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'money_withdrawal' => "required",
			'account_list_id' => "required|integer",
			'transaction_note' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		$data = $this->getDataServices->getWithdrawCurrentValue($checkUser->user_id);
		if(intval($request->money_withdrawal) > intval($data['0']['current_amounts'])){
			$response = [
				'status' => false,
				'message' => "You cannot withdraw more than the current money you have",
				'data' => $request->money_withdrawal,
				'other data' => $data['0']['current_amounts']
			];
			return response()->json($response, 400);
		}	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->saveHistoryWithdraw($request->all());
		$update = $this->actionServices->updateWithdraw($data['0']['current_amounts'],$request->money_withdrawal,$checkUser->user_id);

		return $this->services->response(200,"Withdraw Issued",$getData);
	}
}
