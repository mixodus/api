<?php

namespace App\Http\Controllers\API\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
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

		$checkUser = $this->getDataServices->getAdminbyToken($request);

		if(empty($request['start']))
			$request['start'] = 0;

		if(empty($request['length']))
			$request['length'] = 25;

		$getData = $this->getDataServices->getReferralMember($checkUser->user_id);

		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Referral List",$getData);
		}else{
			return $this->services->response(200,"Referral not found!");
		}
	}
	public function getReferralMemberSuccess(Request $request){
		$checkUser = $this->getDataServices->getAdminbyToken($request);
		$getData = $this->getDataServices->ValidateReferralPoints($checkUser->user_id);

		if ($getData->isEmpty()) {
			return $this->services->response(200,"Referral not found!");
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
		return $this->services->response(200,"Referral List",$getData);
	}
	// kedepannya akan ada upload cv
	public function AssignMember(Request $request){
		$rules = [
			'referral_name' => "required|string",
			'referral_email' => "required|string|email|unique:xin_employees,email",
			'referral_contact_no' => "required|string",
			'file' => "required",
			'fee' => "required|string",
			'job_position' => "nullable|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$file = $request->file('file');
		$imgname = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path('/uploads/referral_file/');
		$file->move($destinationPath,$imgname);
		$request['file'] = $imgname;

		$checkUser = $this->getDataServices->getAdminbyToken($request);

		$checkReferral = $this->getDataServices->ValidateReferralPoints(null,$request->referral_email);
		if (!$checkReferral->isEmpty()) {
			return $this->services->response(401,"Sorry, Your friend is already registered in referral!");
		}
		$status = array('Successful', 'Failed', 'Validating Application', 'Waiting for Interview', 'Under Review');
		$saveReferral = $this->actionServices->saveReferral($request->all(),$checkUser->user_id,$status[0]);
		if(!$saveReferral){
			return $this->services->response(503,"Server Error!");
		}
		return $this->services->response(200,"You have successfully referral your friend.",$request->all());
	}

	public function UpdateReferralMember(Request $request, $id)
	{
		$rules = [
			'referral_name' 		=> "required|string",
			'referral_email' 		=> "required|string|email|unique:xin_employees,email",
			'referral_contact_no' 	=> "required|string",
			'file'					=> "required",
			'fee' 					=> "required|string",
			'job_position' 			=> "nullable|string",
			'referral_employee_id' 	=> "required"
		];

		$checkValidate = $this->services->validate($request->all(),$rules);
		$getData = $this->getDataServices->getReferralMember($id);

		$status = array('Successful', 'Failed', 'Validating Application', 'Waiting for Interview', 'Under Review');
		$saveReferral = $this->actionServices->saveReferral($request->all(),$getData->referral_id,$status[0]);
		if(!$saveReferral){
			return $this->services->response(503,"Server Error!");
		}
		return $this->services->response(200,"You have successfully referral your friend.",$request->all());
	}
	public function UpdateReferralStatus(Request $request)
	{
		$rules = [
			'referral_status' => "required|string",
		];
		$checkValidate = $this->services->validate($request,$rules);
		$getData = $this->getDataServices->getReferralMember($id);

		$status = array('Successful', 'Failed', 'Pending', 'Waiting for Interview');
		$saveReferral = $this->actionServices->saveReferral($request, $getData->referral_id, $status[0]);
		if(!$saveReferral){
			return $this->services->response(503,"Server Error!");
		}
	}
}
