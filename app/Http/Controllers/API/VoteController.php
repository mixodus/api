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
	public function showCandidates(){
		$data = $this->getDataServices->getParticipant();
        return $this->services->response(200, "All Participant", $data);
    }
	public function assignCandidate(Request $request){
		$rules = [
			'vote_themes_id' 	=> "required|integer",
			'name' 	=> "required|string",
			'icon' 	=> "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 
			
		$file = $request->file('banner');
		$fileName = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path().'/uploads/candidate_icon/';
		$file->move($destinationPath,$fileName);

		$request['file_name'] = $fileName;

		$data = $this->actionServices->assignCandidate($request);
		return $this->services->response(200, "Participant Assigned", $data);
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
		$data = $this->getDataServices->getVoteResult($request->id);
        return $this->services->response(200, "Candidate's vote result", $data);
    }
	public function themes(){
		$data = $this->getDataServices->getThemes();
        return $this->services->response(200, "All Themes", $data);
    }
	public function assignTheme(Request $request){
		$rules = [
			'name' 	=> "required|string",
			'banner' 	=> "required|mimes:jpg,png,jpeg|max:5121",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 

		$file = $request->file('banner');
		$fileName = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path().'/uploads/theme_banner/';
		$file->move($destinationPath,$fileName);

		$request['file_name'] = $fileName;

		$data = $this->actionServices->assignTheme($request);
        return $this->services->response(200, "Theme Assigned!", $data);
    }
}
