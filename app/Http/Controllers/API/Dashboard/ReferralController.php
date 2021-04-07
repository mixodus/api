<?php

namespace App\Http\Controllers\API\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Models\ReferralModel;
use App\Models\AdminModel;
use DB;

class ReferralController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	public function getAllMobileReferralMember(){
		$getData = ReferralModel::where('source','mobile')->with('AdminModel')->get();
		return $this->services->response(200,"All Referral Member List", $getData);
	}
	public function getAllWebReferralMember(Request $request){
		$getData = ReferralModel::select('*')->where('source','web')->with('AdminModel');
		if($request->referral_employee_id != null && $request->referral_employee_id !=""){
			$getData->where('referral_employee_id', $request->referral_employee_id);
		}
		$collect = $getData->orderBy('referral_id','DESC')->get();
		if(!$collect->isEmpty()){
			$collect = $collect->map(function($key){
				$key['file_url']  = url('/')."/uploads/referral_file/".$key['file'];
				return $key;
			});
			return $this->services->response(200,"All Referral Member List", $collect);
		}else{
			return $this->services->response(200,"You Have No Referral!");
		}
	}
	public function getReferralByID($id){
		$getData = ReferralModel::select('*')->where('referral_id', $id)->with('AdminModel')->first();
		if(empty($getData)){
			return $this->services->response(404,"Referral Not Found");
		}
		$getData['file_url'] = url('/')."/uploads/referral_file/".$getData['file'];
		return $this->services->response(200,"Data Details By ID", $getData);	
	}
	public function getReferralStatusByID(Request $request, $id){
		$getData = ReferralModel::select('*')->where('referral_id', $id)->with('AdminModel')->first();
		if(!empty($getData)){
			return $this->services->response(200,"Status By ID", $getData);
		}else{
			return $this->services->response(404,"Referral Not Found");
		}
	}
	public function getReferralMember(Request $request){
		$getUser = $this->getDataServices->getAdminbyToken($request);
		if($this->getDataServices->getProperty($getUser, 'role_id')==3){
			$getData = ReferralModel::select('*')->where('source','web')->with('AdminModel');
		
			if($request->referral_employee_id != null && $request->referral_employee_id !=""){
				$getData->where('referral_employee_id', $request->referral_employee_id);
			}
			
			$collect = $getData->orderBy('referral_id','DESC')->get();

			if(!$collect->isEmpty()){
				$collect = $collect->map(function($key){
					$key['file_url']  = url('/')."/uploads/referral_file/".$key['file'];
					return $key;
				});
				return $this->services->response(200,"All Referral Member List", $collect);
			}else{
				return $this->services->response(200,"You Have No Referral!");
			}
		}
		elseif($getUser->role_id == 1 || $getUser->role_id == 0){
			$getData = ReferralModel::select('*')->with('AdminModel');
			if(!empty($getData)){
				$collect = $getData->orderBy('referral_id','DESC')->get();
				if(!$collect->isEmpty()){
					$collect = $collect->map(function($key){
						$key['file_url']  = url('/')."/uploads/referral_file/".$key['file'];
						return $key;
					});
					return $this->services->response(200,"All Referral Member List", $collect);
				}
			}
			else{
				return $this->services->response(404,"Referral Not Found");
			}
		}
		else{
			return $this->services->response(404,"You have no access");
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
			'source' => "required|in:web,mobile",
			'referral_name' => "required|string",
			'referral_email' => "required|string|email|unique:xin_employees,email",
			'referral_contact_no' => "required|string",
			'referral_status' => "required|string|in:Success,Pending,InReview,Failed",
			'referral_employee_id' => "required|string",
			'file' => "required",
			'fee' => "required|string",
			'job_position' => "nullable|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate; 

		$file = $request->file('file');
		$fileName = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path().'/uploads/referral_file/';
		$file->move($destinationPath,$fileName);

		$request['file_name'] = $fileName;

		$status = array('Successful', 'Failed', 'Validating Application', 'Waiting for Interview', 'Under Review');

		$checkReferral = $this->getDataServices->checkMemberReferral(null,$request->referral_email);
		if (!$checkReferral->isEmpty()) {
			return $this->services->response(401,"Sorry, Your friend is already registered in referral!");
		}

		$postParam = array(
			'source' => $request['source'],
			'referral_name' => $request['referral_name'],
			'referral_email' => $request['referral_email'],
			'referral_contact_no' => $request['referral_contact_no'],
			'referral_status' => $request['referral_status'], 
			'referral_employee_id' => $request['referral_employee_id'],
			'file' => $request['file_name'],
			'fee' => $request['fee'],
			'job_position' => $request['job_position'],
			'created_at' => date('Y-m-d h:i:s'),
			'modified_at' => date('Y-m-d h:i:s')
		);

		ReferralModel::create($postParam);
		return $this->services->response(200,"Member Assigned",$request->all()); 
	}

	public function UpdateReferralMember(Request $request, $id)
	{
		$rules = [
			'referral_name' 	=> "required|string",
			'referral_email' 	=> "required|string|email|unique:xin_employees,email",
			'referral_contact_no' 	=> "required|string",
			'fee' 			=> "required|string",
			'job_position' 		=> "nullable|string",
			'referral_employee_id' 	=> "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

		$referralData = $this->actionServices->getReferralData($id);

		if(!$referralData){
           		return $this->actionServices->response(404,"Referral doesnt exist!");
        	}

		if(!empty($request->file)){
            		$file = $request->file('file');
            		$name_file = $file->getClientOriginalName();
        	}
		$filename = $referralData->file;

		if($request->file != '' && $name_file != $referralData->file){
            		$folder = public_path().'/uploads/referral_file/';

            		if($referralData->file != '' && $referralData->file != null){
                		$file_old = $folder.$referralData->file;
                		unlink($file_old);
            		}  
            		$extension = $file->getClientOriginalExtension();
            		$filename = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
            		$file->move($folder, $filename);
        	}

		//$status = array('Successful', 'Failed', 'Validating Application', 'Waiting for Interview', 'Under Review');
		$saveReferral = $this->actionServices->UpdateReferralMember($request->all(),$id, $filename);
		if(!$saveReferral){
			return $this->services->response(503,"Server Error!");
		}
		return $this->services->response(200,"Referral Updated.",$request->all());
	}
	public function UpdateReferralStatus(Request $request, $id)
	{
		$rules = ['referral_status' => "required|string|in:Success,Pending,InReview,Failed"];

		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }

		$saveReferral = $this->actionServices->UpdateReferralStatus($request->all(), $id);
		if(!$saveReferral){
			return $this->services->response(503,"Server Error!");
		}
		return $this->services->response(200,"Referral Status Updated.",$request->all());
	}
}
