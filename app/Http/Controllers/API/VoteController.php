<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
	public function topics(Request $request){
        $getUser = $getUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->getTopics();
        return $this->actionServices->response(200, "All Topics", $data, $action);
    }
	public function showCandidates(Request $request){
		$getUser = $this->getDataServices->getUserbyToken($request);
		if(!$getUser){
			return $this->services->response(406, "User Not Found!");
		}

		$data = $this->getDataServices->getCandidate($request, $getUser);
		if($data == null){
			return $this->services->response(406, "Topic Not Found!", $data);
		}
        return $this->services->response(200, "All Participant", $data);
    }
	public function assignVote(Request $request){
		$getUser = $this->getDataServices->getUserbyToken($request);
		if(!$getUser){
			return $this->services->response(406, "User Not Found!");
		}

		$data = $this->actionServices->assignVote($request, $getUser);
		if(empty($data)){
			return $this->services->response(406, "Candidate not found!", $data);
		}
		if($data == "false"){
			return $this->services->response(406, "You have no more votes left!");
		}
		return $this->services->response(200, "Vote Success!", $data);
	}
	public function voteResult(Request $request){
		$data = $this->getDataServices->getVoteResult($request->vote_topic_id);
		if(empty($data)){
			return $this->services->response(406, "Vote Result not found!");
		}
        return $this->services->response(200, "Candidate's vote result", $data);
    }
	public function resetVote(){
		$this->actionServices->resetVote();
        return $this->services->response(200, "Vote reset!");
	}
}
