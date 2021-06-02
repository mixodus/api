<?php

namespace App\Http\Controllers\API\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\Dashboard\GetDataServices;

class VoteController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
	}
	public function showCandidates(Request $request){
		$getUser = $this->getDataServices->getAdminbyToken($request);
		if(!$getUser){
			return $this->services->response(406, "User Not Found!");
		}
		$action = $this->actionServices->getactionrole($getUser->role_id, 'voting-details');

		$data = $this->getDataServices->getCandidate($request, $getUser);
        return $this->actionServices->response(200, "All Participant", $data, $action);
    }

	public function showCandidateByID(Request $request, $id){
		$getUser = $this->getDataServices->getAdminbyToken($request);
		if(!$getUser){
			return $this->services->response(406, "User Not Found!");
		}
		$action = $this->actionServices->getactionrole($getUser->role_id, 'voting-details');

		$data = $this->getDataServices->getCandidateByID($id);
        return $this->actionServices->response(200, "Participant", $data, $action);
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
		if(empty($data)){
			return $this->services->response(406, "Assigned Topic not Found!");
		}
		return $this->services->response(200, "Participant Assigned", $data);
	}
	public function updateCandidate(Request $request, $id){
		$rules = [
			'vote_topic_id' 	=> "required|integer",
			'name' 	=> "required|string",
			'icon' 	=> "mimes:jpg,png,jpeg|max:5121",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 

		$candidateData = $this->getDataServices->getCandidateByID($id);

		if(!$candidateData){
           		return $this->actionServices->response(404,"Candidate doesn't exist!");
        	}

		if(!empty($request->icon)){
            		$file = $request->file('icon');
            		$name_file = $file->getClientOriginalName();
        }

		$filename = $candidateData->icon;

		if($request->icon != '' && $name_file != $candidateData->icon){
			$folder = public_path().'/uploads/candidate_icon/';
			if($candidateData->icon != '' && $candidateData->icon != null){
				$file_old = $folder.$candidateData->icon;
				if(file_exists($file_old)){
					unlink($file_old);
				}
			}  
			$extension = $file->getClientOriginalExtension();
			$filename = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
			$file->move($folder, $filename);
		}

		$data = $this->actionServices->updateCandidate($request, $id, $filename);
		if(empty($data)){
			return $this->services->response(406, "Assigned Participant not Found!");
		}
		return $this->services->response(200, "Participant Updated", $data);
	}
	public function deleteCandidate($id){
		$data = $this->actionServices->deleteCandidate($id);
		if(empty($data)){
			return $this->services->response(406, "Participant not Found!");
		}
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
		$data = $this->getDataServices->getVoteResult($request->vote_topic_id);
		if(empty($data)){
			return $this->services->response(406, "Vote Result not found!");
		}
        return $this->services->response(200, "Candidate's vote result", $data);
    }
	public function topics(Request $request){
        $getUser = $this->getDataServices->getAdminByToken($request);
        $action = $this->actionServices->getactionrole($getUser->role_id, 'voting');
		$data = $this->getDataServices->getTopics();
        return $this->actionServices->response(200, "All Topics", $data, $action);
    }
	public function topicByID(Request $request, $id){
        $getUser = $this->getDataServices->getAdminByToken($request);
        $action = $this->actionServices->getactionrole($getUser->role_id, 'voting');
		$data = $this->getDataServices->getTopicsByID($id);
        return $this->services->response(200, "Topics", $data, $action);
    }
	public function assignTopic(Request $request){
		$rules = [
			'name' 	=> "required|string",
			'banner' 	=> "required|mimes:jpg,png,jpeg|max:5121",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 

		$file = $request->file('banner');
		$fileName = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
		$destinationPath = public_path().'/uploads/topic_banner/';
		$file->move($destinationPath,$fileName);

		$request['file_name'] = $fileName;

		$data = $this->actionServices->assignTopic($request);
        return $this->services->response(200, "Topic Assigned!", $data);
    }
	public function updateTopic(Request $request, $id){
		$rules = [
			'name' 	=> "required|string",
			'title' => "required|string",
			'banner' => "mimes:jpg,png,jpeg|max:5121"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
		if(!empty($checkValidate))
			return $checkValidate; 

		$topicData = $this->getDataServices->getTopicsByID($id);

		if(!$topicData){
           		return $this->actionServices->response(404,"Topic doesn't exist!");
        	}

		if(!empty($request->banner)){
            		$file = $request->file('banner');
            		$name_file = $file->getClientOriginalName();
        }

		$filename = $topicData->banner;

		if($request->banner != '' && $name_file != $topicData->banner){
			$folder = public_path().'/uploads/topic_banner/';
			if($topicData->banner != '' && $topicData->banner != null){
				$file_old = $folder.$topicData->banner;
				if(file_exists($file_old)){
					unlink($file_old);
				}
			}  
			$extension = $file->getClientOriginalExtension();
			$filename = '-'.round(microtime(true)).'-'.$file->getClientOriginalName();
			$file->move($folder, $filename);
		}

		$data = $this->actionServices->updateTopic($request, $id, $filename);
        return $this->services->response(200, "Topic Assigned!", $data);
    }
	public function deleteTopic($id){
        $data = $this->actionServices->deleteTopic($id);
		if(empty($data)){
			return $this->services->response(406, "Topic not Found!");
		}
		return $this->services->response(200, "Topic Deleted", $data);
    }
}
