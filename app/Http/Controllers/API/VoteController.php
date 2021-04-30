<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class VoteController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	public function getParticipant(){
		$data = $this->getDataServices->getParticipant();
        return $this->services->response(200, "All Participant", $data);
    }
	public function doVote(Request $request){
		$getUser = $this->getDataServices->getUserbyToken($request);
		if(!$getUser){
			return $this->services->response(406, "User Not Found!");
		}

		$data = $this->actionServices->assignVote($request);
		return $this->services->response(200, "Vote Success!", $data);
	}
}
