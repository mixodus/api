<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\ActivitiesPointModel;

class EducationController extends Controller
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
		$getData = $this->getDataServices->employeeQualification($checkUser->user_id);	
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Pendidikan",$getData);
		}else{
			return $this->services->response(200,"Pendidikan",array());
		}
	}
	public function create(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'name' => "required|string",
			'education_level_id' => "required",
			'start_period_month' => "required|string",
			'start_period_year' => "required|string",
			'end_period_month' => "required|string",
			'end_period_year' => "required|string",
			'description' => "nullable|string",
			'field_of_study' => "nullable|string",
			'gpa' => "nullable"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->saveEducation($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Pendidikan berhasil ditambahkan');
		$getPoint = $this->activity_point->where('activity_point_code', 'add_education')->first();
		if($getPoint) {
			$save_trx_point = $this->actionServices->postTrxPoints("add_education",$getPoint->activity_point_point,$checkUser->user_id,0,1);
		}
		return $this->services->response(200,"Pendidikan berhasil ditambahkan",$request->all());
	}
	public function update(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'id' => "required",
			'name' => "required|string",
			'education_level_id' => "required",
			'start_period_month' => "required|string",
			'start_period_year' => "required|string",
			'end_period_month' => "required|string",
			'end_period_year' => "required|string",
			'description' => "nullable|string",
			'field_of_study' => "nullable|string",
			'gpa' => "nullable"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->updateEducation($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(503,"Terjadi kesalahan jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Pendidikan berhasil di update');
		
		return $this->services->response(200,"Pendidikan berhasil di update.",$request->all());
	}
	public function delete(Request $request){
		$rules = [
			'id' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$save = $this->actionServices->deleteEmployeeEducation($request->id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Pendidikan telah berhasil dihapus');
		
		return $this->services->response(200,"Pendidikan berhasil dihapus.",array());
	}
	
}
