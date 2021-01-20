<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\ActivitiesPointModel;

class ProjectExperienceController extends Controller
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
		$getData = $this->getDataServices->getWorkExperience($checkUser->user_id);	
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Pengalaman Proyek",$getData);
		}else{
			return $this->services->response(200,"Pengalaman Proyek",array());
		}
	}
	public function create(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'work_experience_id' => "required",
			'project_name' => "required|string",
			'start_period_month' => "required|integer",
			'start_period_year' => "required|integer",
			'position' => "required|string",
			'end_period_month' => "required|integer",
			'end_period_year' => "required|integer",
			'jobdesc' => "required|string",
			'tools' => "nullable|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->saveEmployeeProjectExperience($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(406,"Kesalahan Jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Pengalaman Proyek berhasil ditambahkan.');
		$getPoint = $this->activity_point->where('activity_point_code', 'add_project')->first();
		if($getPoint) {
			$save_trx_point = $this->actionServices->postTrxPoints("add_project",$getPoint->activity_point_point,$checkUser->user_id,0,1);
		}
		return $this->services->response(200,"Pengalaman Proyek berhasil ditambahkan.",$request->all());
	}
	public function update(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'id' => "required",
			'work_experience_id' => "required",
			'project_name' => "required|string",
			'start_period_month' => "required|integer",
			'start_period_year' => "required|integer",
			'position' => "required|string",
			'end_period_month' => "required|integer",
			'end_period_year' => "required|integer",
			'jobdesc' => "required|string",
			'tools' => "nullable|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$save = $this->actionServices->updateEmployeeProjectExperience($request->all(),$checkUser->user_id);
		if(!$save){
			return $this->services->response(406,"Kesalahan Jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Pengalaman Proyek berhasil diperbaharui.');
		
		return $this->services->response(200,"Pengalaman Proyek berhasil diperbaharui.",$request->all());
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
		$save = $this->actionServices->deleteEmployeeProjectExperience($request->id);
		if(!$save){
			return $this->services->response(406,"Kesalahan Jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Pengalaman Proyek berhasil dihapus');
		
		return $this->services->response(200,"Pengalaman Proyek berhasil dihapus.",array());
	}
}
