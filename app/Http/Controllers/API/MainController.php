<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;;
use App\Models\AppVersionModel;

class MainController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->api_version = 2;
		$this->url_download = "https://play.google.com/store/apps/details?id=com.idstar.mobile";    
		$this->max_fee_referral = "1.500.000";
	}
	public function index(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		if (!$checkUser) {
			return $this->services->response(406,"User tidak ditemukan!",array());
		}
		$data['user'] = $this->getDataServices->userDetail($checkUser->user_id);
		$data['friends'] = $this->getDataServices->get_all_friends_complete($checkUser->user_id);
		$data['events'] = $this->getDataServices->homeEvent($checkUser->user_id);
		$data['banner'] = $this->getDataServices->getHomeBanner(5);
		$data['news'] = $this->getDataServices->getNews(null,4);
		$data['count_applied_jobs'] = count($this->getDataServices->userJobsApplication($checkUser->user_id));$data['info'] = array('api_version'=>$this->api_version,'url_download'=>$this->url_download,'max_fee_referral'=>$this->max_fee_referral);
		$data['info'] = array('api_version'=>$this->api_version,'url_download'=>$this->url_download,'max_fee_referral'=>$this->max_fee_referral);
		$data['friend_list']['data'] = array(); //not done
		$data['friend_request']['data']  = array(); //not done
		$data['flyer_banner'] = array('is_active'=>true,'url_banner'=> url('/')."/uploads/event/hackathon/general/flyer_hackathon.png");
		if($checkUser->user_id != 0){
			$data['user']->makeHidden(['qualification','history','project','certification','work_experience','mutual_friends','total_achievement']);

		}if ($checkUser) {
			return $this->services->response(200,"Data",$data);
		}else{
			return $this->services->response(200,"Data tidak ditemukan!",array());
		}

	}
	public function allOngoing(Request $request){
		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		
		$data = [
			'ongoing_event' => $this->getDataServices->getEventList(),
			'ongoing_challenge' =>  $this->getDataServices->getChallengeOngoing($request->start,$request->length)
		];
		return $this->services->response(200,"Daftar Event yang sedang berlangsung",$data);
	}
	public function level(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->userDetail($checkUser->user_id);
		$data->points = $this->getDataServices->totalTrxPointbyUserId($checkUser->user_id);
		
		$getLevel = $this->getDataServices->getLevel();
	   
		$current_level = array();

		foreach($getLevel as $row) {
			$row->is_passed = false;
			$row->is_accomplished = false;
			if( ($data->points >= $row->level_min_point  && $data->points <= $row->level_max_point )){
				$row->is_passed = true;
				$row->is_accomplished = false;
				$current_level = $row;
			}else if($data->points > $row->level_max_point ) {
				$row->is_passed = true;
				$row->is_accomplished = true;
			}
		}
		$response = [
			'status' 	=> true,
			'message' 	=> 'Level List',
			'data'	 	=> $getLevel,
			'current_level' =>$current_level,
			'user' => $data
		];
		
		return response()->json($response, 200);
	}
	public function homeEvent(Request $request){
		$data['banner'] = $this->getDataServices->getBannerEvent(5);
		
		return $this->services->response(200,"Banner Event",$data);
	}
	public function homeNews(Request $request){
		$data['banner'] = $this->getDataServices->getBannerNews(5);
		
		return $this->services->response(200,"Banner News",$data);
	}
	public function checkSession(Request $request){
		
		$checkUser = $this->getDataServices->getUserbyToken($request);

		$response = [
			'status' => true,
			'message' => "Check session OK"
		];
		return response()->json($response, 200);
    }
	public function checkVersion(Request $request){
		$rules = [
			'version' => "required|string",
			'type' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		
		$checkVersion = AppVersionModel::select('*')->orderBy('app_version_id','DESC')->first();
		if($request->version != $checkVersion->version){
			
			return $this->services->response(406,"Segera update aplikasi ke version terbaru",$checkVersion);
		}
		
		if($request->type == "android"){
			$checkVersion->url_update = "https://play.google.com/store/apps/details?id=com.onetalents.mobile";
		}
		return $this->services->response(200,"Success",$checkVersion);
	}
    
}
