<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\ActivitiesPointModel;

class WorkExperienceController extends Controller
{
	private $activity_point;

	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->activity_point = ActivitiesPointModel::select('*');
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
		$getData = $this->getDataServices->employeeExperiences($checkUser->user_id);
		
		return $this->services->response(200,"Work Experience",$getData);
	}
    public function create(Request $request){
        $checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'company_name' => "required|string",
			'start_period_month' => "required|string",
			'start_period_year' => "required|string",
			'end_period_month' => "required|string",
			'end_period_year' => "required|string",
			'post' => "required|string",
			'description' => "nullable|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
		$save = $this->actionServices->saveEmployeeWorkExperience($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Server Error!");
        } 
        $save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Work Experience successfully added');
        $getPoint = $this->activity_point->where('activity_point_code', 'add_work_experience')->first();
		if($getPoint) {
			$save_trx_point = $this->actionServices->postTrxPoints("add_work_experience",$getPoint->activity_point_point,$checkUser->user_id,0,1);
		}
		return $this->services->response(200,"Work experiences successfully added.",$request->all());
    }
    public function update(Request $request){
        $checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
            'id' => "required",
			'company_name' => "required",
			'start_period_month' => "required|string",
			'start_period_year' => "required|string",
			'end_period_month' => "required|string",
			'end_period_year' => "required|string",
			'post' => "required|string",
			'description' => "nullable|string"
        ];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
		$save = $this->actionServices->updateEmployeeWorkExperience($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Server Error!");
        } 
        $save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Project experiences successfully updated');
        
		return $this->services->response(200,"Project experiences successfully updated.",$request->all());
	}
	public function delete(Request $request){
        $checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = ['id' => "required"];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
		$save = $this->actionServices->deleteEmployeeWorkExperience($request->id);
		if(!$save){
			return $this->services->response(503,"Server Error!");
        } 
        $save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Work Experience successfully deleted');

		return $this->services->response(200,"Work experiences successfully deleted.",$request->all());
    }
}