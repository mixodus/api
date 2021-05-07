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
	public function showCandidates(Request $request){
		$data = $this->getDataServices->getCandidate($request);
        return $this->services->response(200, "All Participant", $data);
    }
	public function assignCandidate(Request $request){
		$rules = [
			'vote_topic_id' 	=> "required|integer",
			'name' 	=> "required|string",
			'icon' 	=> "required|mimes:jpg,png,jpeg|max:5121",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 
			
		$file = $request->file('icon');
		$fileName = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path().'/uploads/candidate_icon/';
		$file->move($destinationPath,$fileName);

		$request['file_name'] = $fileName;

		$data = $this->actionServices->assignCandidate($request);
		return $this->services->response(200, "Participant Assigned", $data);
	}
	public function updateCandidate(Request $request, $id){
		$rules = [
			'vote_topic_id' 	=> "required|integer",
			'name' 	=> "required|string",
			'icon' 	=> "required|mimes:jpg,png,jpeg|max:5121",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 
			
		$file = $request->file('icon');
		$fileName = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path().'/uploads/candidate_icon/';
		$file->move($destinationPath,$fileName);

		$request['file_name'] = $fileName;

		$data = $this->actionServices->updateCandidate($request, $id);
		return $this->services->response(200, "Participant Updated", $data);
	}
	public function deleteCandidate(Request $request){
		$data = $this->actionServices->deleteCandidate($request);
		return $this->services->response(200, "Participant Deleted", $data);
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
	public function updateTheme(Request $request, $id){
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

		$data = $this->actionServices->updateTheme($request, $id);
        return $this->services->response(200, "Theme Assigned!", $data);
    }
	public function voteStatus(Request $request){
		$getUser = $this->getDataServices->getUserbyToken($request);
		if(!$getUser){
			return $this->services->response(406, "User Not Found!");
		}

		$rules = [
			'topic_id' 	=> "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 

		$temp = $this->actionServices->checkVote($request, $getUser);
		$data['exist'] = $temp;
		return $this->services->response(200, "Vote Status", $data); 
	}
}
