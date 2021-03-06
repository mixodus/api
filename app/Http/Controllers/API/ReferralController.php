<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use DB;

class ReferralController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	public function getReferralMember(Request $request){
		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
	   	
		$checkUser = $this->getDataServices->getUserbyToken($request);

		if(empty($request['start']))
			$request['start'] = 0;
		
		if(empty($request['length'])) 
			$request['length'] = 25;
		
		$getData = $this->getDataServices->getReferralMember($checkUser->user_id);
		
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Daftar Rujukan",$getData);
		}else{
			return $this->services->response(200,"Rujukan tidak ditemukan",array());
		}
	}
	public function getReferralMemberSuccess(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->ValidateReferralPoints($checkUser->user_id);
	  
		if ($getData->isEmpty()) {
			return $this->services->response(200,"Rujukan tidak ditemukan");
		}
		//update data (gatau fungsinya ada di code lama)
		foreach($getData as $row){
			if($row->added_yet == 1){
				DB::raw(
					"UPDATE xin_withdraw SET current_amount = current_amount + '.$row->withdraw_reward.');"
				);
			} 
		}
		
		$checkPoint = $this->getDataServices->ValidateReferralPoints();
		if (!$checkPoint->isEmpty()) {
			$getPoint = $this->getDataServices->getActivityPoint('successful_referral');
			if($getPoint) {
				$save_trx_point = $this->actionServices->postTrxPoints("successful_referral",$getPoint->activity_point_point,$checkUser->user_id,0,1);
			}
			foreach($checkPoint as $row){
				$updateStatusRefferal = $this->actionServices->updateReferral("successful_referral",$row->referral_id);
			}
		}
		return $this->services->response(200,"Daftar Rujukan",$getData);
	}
	// kedepannya akan ada upload cv
	public function AssignMember(Request $request){
		$rules = [
			'referral_name' => "required|string",
			'referral_email' => "required|string|email|unique:xin_employees,email",
			'referral_contact_no' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
	   	
		$checkUser = $this->getDataServices->getUserbyToken($request);
		
		$checkReferral = $this->getDataServices->ValidateReferralPoints(null,$request->referral_email);
		if (!$checkReferral->isEmpty()) {
			return $this->services->response(401,"Maaf, teman Anda telah terdaftar pada rujukan. ");
		}
		$status = array('Successful', 'Failed', 'Validating Application', 'Waiting for Interview', 'Under Review');
		$saveReferral = $this->actionServices->saveReferral($request->all(),$checkUser->user_id,$status[0]);
		if(!$saveReferral){
			return $this->services->response(503,"Koneksi jaringan bermasalah!");
		}  
		return $this->services->response(200,"Anda telah berhasil membuat rujukan.",$saveReferral);
	}   
	public function uploadCV(Request $request,$id){

		$file = $request->file('userfile');
		$imgname = $file->getClientOriginalName().round(microtime(true));
		$destinationPath = public_path('/uploads/referral/');
		$file->move($destinationPath,$imgname);
		
		$request['file'] = $imgname;
		$request['id'] = $id;
		$upload = $this->actionServices->updateReferralfile($request->all());
		if(!$upload){
			return $this->services->response(503,"Koneksi jaringan bermasalah!");
		} 
		return $this->services->response(200,"Anda telah berhasil membuat rujukan.",$request->all());
	}
}
