<?php

namespace App\Http\Controllers\APi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class PointController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
        $this->getDataServices = new GetDataServices();
    }
        
    public function leaderboardMonth(){

        $data = $this->getDataServices->getleaderboardMonth();
        
		return $this->services->response(200,"Leaderboard Bulan ini",$data);
       
    }
    public function index(Request $request){
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
			'message' 	=> 'Daftar Level',
			'data'	 	=> $getLevel,
			'current_level' =>$current_level,
			'user' => $data
		];
		
		return response()->json($response, 200);
        
    }
}
