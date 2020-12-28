<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\JobsModel;

class JobsController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->jobsModel = JobsModel::select('*');
	}

	public function index(Request $request){
		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer",
			'name' => "nullable|string",
			'q' => "nullable|string",
			'range_salary_start' => "nullable|integer",
			'range_salary_end' => "nullable|integer",
			'country_id' => "nullable|integer",
			'province_id' => "nullable|integer",
			'city_id' => "nullable|integer",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
	   	
		if($request['q'] != null || $request['q'] !=""){
			$getData = $this->getDataServices->getJobs(null,null,$request['q']);	
		}else{
			$getData = $this->getDataServices->getJobs();
		}
	
		return $this->services->response(200,"Job Posting",$getData);
	}
	public function detail(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->getJobs($request->id,$checkUser->user_id);	
		if ($getData) {
			$getData->makeHidden('applications');
			return $this->services->response(200,"Job Posting",$getData);
		}else{
			return $this->services->response(404,"Job doesn't exist!",array());
		}
	}

	public function userJobsApplication(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->userJobsApplication($checkUser->user_id);	
	   
		if(count($getData)>0){
			return $this->services->response(200,"Job application",$getData);
		}else{
			return $this->services->response(404,"Job application not found",array());
		}
	}
	public function applyJobsApplication(Request $request){
	   
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'job_id' => "required|integer",
			'email' => "nullable|string",
			'contact_no' => "nullable|max:13"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

		$checkUserApply = $this->getDataServices->userJobsApplication($checkUser->user_id,$request->job_id);
		if(count($checkUserApply)>0){
			return $this->services->response(400,"Already Applied");
		}
		
		$getData = $this->getDataServices->getJobs($request->job_id,null,null);	

		$saveJobs = $this->actionServices->applyJob($request->job_id,$checkUser->user_id,$request->email,$request->contact_no);
		$save_notif = $this->actionServices->postNotif(4,$request->job_id,$checkUser->user_id,'You are successfully apply to ' .$getData->job_title. ' job');
		return $this->services->response(200,"You are successfully apply this job!", array());        
	}
	//================Jobs Type
	public function getJobTypeList(Request $request){
		$getData = $this->getDataServices->getJobTypeList();	
		if ($getData) {
			return $this->services->response(200,"Job Type",$getData);
		}else{
			return $this->services->response(200,"Job Type doesn't exist!",array());
		}
	}
}
