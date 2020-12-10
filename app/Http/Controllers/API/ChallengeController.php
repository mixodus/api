<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\ChallengeParticipants;

class ChallengeController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
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
	   	
		$getData = $this->getDataServices->getChallenge("active");
		
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Challenge List",$getData);
		}else{
			return $this->services->response(200,"Challenge not found!");
		}
	}
	public function detail(Request $request,$id){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->getChallenge("detail",$id,$checkUser->user_id);
		
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Challenge Detail",$getData[0]);
		}else{
			return $this->services->response(200,"Challenge not found!");
		}
	}
	public function history(Request $request){
		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
	   	
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getData = $this->getDataServices->getChallengebyUser($checkUser->user_id);
		
		if (!$getData->isEmpty()) {
			return $this->services->response(200,"Challenge List",$getData);
		}else{
			return $this->services->response(200,"Challenge not found!");
		}
	}
	public function join(Request $request){
		$rules = [
			'challenge_id' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
	   	
		$checkUser = $this->getDataServices->getUserbyToken($request);
	
		$checkChallenge = $this->getDataServices->checkChallengeJoin($request->challenge_id,$checkUser->user_id);
		if(!empty($checkChallenge)){
			return $this->services->response(401,"Sorry, You are already registered this challenge");
		}
		$data = $this->getDataServices->getChallengeRaw($request->challenge_id);

		$saveJobs = $this->actionServices->joinChallenge($data,$checkUser->user_id);
		return $this->services->response(200,"You are successfully registered to this challenge!", $data);  
	}
	public function quiz(Request $request){
		$rules = [
			'challenge_id' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
	   	
		$checkUser = $this->getDataServices->getUserbyToken($request);
	
		$checkJoin = $this->getDataServices->getChallenge("detail",$request->challenge_id,$checkUser->user_id);
		if(count($checkJoin[0]['me'])==0){
			return $this->services->response(401,"You have not join this challenge");
		}
		$getQuiz = array();
		if(!empty($checkJoin[0]['me']['list_quiz_id']) && $checkJoin[0]['me']['list_quiz_id']!=null && $checkJoin[0]['me']['list_quiz_id']!="") {
			$str_list_quiz = str_replace('##',",",$checkJoin[0]['me']['list_quiz_id']);
			$str_list_quiz = str_replace("#","",$str_list_quiz);
			$arr_id_quiz = explode(",",$str_list_quiz);
		
			$getQuiz = $this->getDataServices->getChallengeQuiz($arr_id_quiz);
		}else{
			$getQuiz = $this->getDataServices->getChallengeQuiz($request->challenge_id);
		}
		if (count($getQuiz)>0) {
			return $this->services->response(200,"Quiz List",$getQuiz);
		}else{
			return $this->services->response(200,"Quiz not found!");
		}
	}
	public function answer(Request $request){
		$rules = [
			'quiz_id' => "required|integer",
			'quiz_answer' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
	   	
		$checkUser = $this->getDataServices->getUserbyToken($request);
	
		$checkJoin = $this->getDataServices->getChallenge("quiz",null,$checkUser->user_id,$request->quiz_id);
		$challenge_register = $this->getDataServices->checkChallengeJoin($checkJoin->challenge_id,$checkUser->user_id);
		
		if(empty($challenge_register)){
			return $this->services->response(401,"You have not join this challenge");
		}
		$quiz_check = "#".$request->quiz_id."#";
		$quiz_answer = "#".$request->quiz_answer."#";
	
		if(!empty($challenge_register['list_quiz_id']) && strpos($challenge_register['list_quiz_id'], $quiz_check ) !== false) {
			return $this->services->response(401,"You have answer quiz with ID #".$request->quiz_id);
		}
		$challenge_register->list_quiz_id = $challenge_register['list_quiz_id'] . $quiz_check;
		$challenge_register->list_quiz_answer = $challenge_register['list_quiz_answer'] . $quiz_answer;
		
		if($request->quiz_answer == $checkJoin['quiz'][0]['answer']) {
			$challenge_register->total_current_point = $challenge_register['total_current_point'] + $checkJoin['challenge_point_every_task'];
		}
		$challenge_register->total_current_task = $challenge_register['total_current_task']+1;
		
		if($challenge_register->total_current_task == $checkJoin->total_task) {
			$save_notif = $this->actionServices->postNotif(2,$checkJoin->challenge_id,$checkUser->user_id,'You Completed ' .$checkJoin->challenge_title.' Challenge');
			$save_point = $this->actionServices->postTrxPoints('',$challenge_register->total_current_point,$checkJoin->challenge_id,1);
		}
		$challenge_register->is_achieve = true;
		ChallengeParticipants::where('id',$challenge_register['id'])->update($challenge_register->toArray());

		return $this->services->response(200,"You are successfully submit answer",array());
	}

	public function achievement(Request $request){
		$action = ['19-view', '20-view', '20-add', '20-edit', '20-delete', '21-view'];
		$valn = [];
		foreach($action as $a)
		{
			$tes= explode("-", $a);
			$permission= ['menu_id' => $tes[0], 'action' =>$tes[1]]; 
			array_push($valn, $permission);
		}
		$data['permission'] = $valn;
		return $data;
		
		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		if($request->start == null){
			$request->start = 0;
		}
		if($request->length == null){
			$request->length = 3;
		}
		$getAwards = $this->getDataServices->getAwardsbyUserId($checkUser->user_id,$request->start,$request->length);
		
		return $this->services->response(200,"List Achievement",$getAwards);
	}
	public function achievementAll(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getAwards = $this->getDataServices->getAwardsbyUserId($checkUser->user_id);
		
		return $this->services->response(200,"List Achievement",$getAwards);
	}

}
