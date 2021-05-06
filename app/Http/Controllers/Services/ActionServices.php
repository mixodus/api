<?php

namespace App\Http\Controllers\Services;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TransactionsPoints;
use App\Models\ResetPasswordModel;
use App\Models\UserModels;
use App\Models\NotifModel;
use App\Models\LevelModel;
use App\Models\AwardModel;
use App\Models\EventParticipantModel;
use App\Models\EventModel;
use App\Models\EventParticipantStatusModel;
use App\Models\EventScheduleModel;
use App\Models\EmployeeWorkExperienceModel;
use App\Models\EmployeeQualificationModel;
use App\Models\EmployeeProjectExperienceModel;
use App\Models\EmployeeCertification;
use App\Models\ChallengeParticipants;
use App\Models\JobsApplicationModel;
use App\Models\WithdrawRewardModel;
use App\Models\ReferralModel;
use App\Models\FriendModel;
use App\Models\UserBankModel;
use App\Models\UserWithdrawModel;
use App\Models\UserWithdrawHistoryModel;
use App\Models\VoteChoiceModel;
use App\Models\VoteChoiceSubmitModel;
use App\Models\VoteThemeModel;
use Firebase\JWT\JWT;
//fase2
use App\Models\Fase2\NewsCommentModel;
use App\Models\Fase2\NewsCommentReplyModel;
use App\Models\Fase2\JobTypeModel;
use App\Models\Fase2\EmployeeCV;

class ActionServices extends BaseController
{
	public function __construct(){}
	
	public function postTrxPoints($code, $point, $user_id,$challenge_id = "",$status = ""){
		$PostPoint['activity_point_code'] = $code;
		$PostPoint['point'] = $point;
		$PostPoint['employee_id'] = $user_id;
		$PostPoint['challenge_id'] = $challenge_id;
		$PostPoint['status'] = $status;
		$PostPoint['created_at'] = date('Y-m-d h:i:s');
		
		TransactionsPoints::create($PostPoint);
	}

	public function postResetPassword($email,$code){
		$PostCode['code'] = $code;
		$PostCode['expired_at'] = date("Y-m-d H:i:s", strtotime('+24 hours'));
		$PostCode['email'] = $email;
		$PostCode['created_at'] = date('Y-m-d h:i:s');
		ResetPasswordModel::create($PostCode);

	}

	public function postNotif($type,$detail_id,$user_id,$desc){
		$data_notif = array(
			'notif_type_id' => $type,
			'notif_detail_id' => $detail_id,
			'user_id' => $user_id,
			'title' => $desc,
			'description' => $desc,
			'created_at' => date('Y-m-d h:i:s'),
			'modified_at' => date('Y-m-d h:i:s')
		);	
		NotifModel::create($data_notif);
	}

	public function uploadFile(Request $request){
		$file = $request->file($request->param);
		$imgname = 'file_'.date('Ymd').'_'.date('his').'.'.$file->getClientOriginalExtension();
		$saveFile = $file->move(public_path($request->path.$imgname));

		return $imgname;
	}

	public function applyJob($job_id,$user_id,$email,$contact_no){
		$data = array(
			'job_id' => $job_id,
			'user_id' => $user_id,
			'email' =>$email,
			'contact_no' =>$contact_no,
			'message' =>"",
			'job_resume'=>"",
			'application_status' => 'Applied',
			'application_remarks'=>"",
			'created_at' => date('Y-m-d h:i:s')
		);
		JobsApplicationModel::create($data);
	}

	public function postParticipantEvent($data,$user_id){
		$data = array(
			'event_id' => $data['event_id'],
			'employee_id' => $user_id,
			'email' => $data['email'],
			'fullname' => $data['fullname'],
			'date_of_birth' => $data['date_of_birth'],
			'address' => $data['address'],
			'country' => $data['country'],
			'city' => $data['city'],
			'gender' => $data['gender'],
			'status' => 'Approved',
			'modified_at' => date('Y-m-d h:i:s')
		);
		$save = EventParticipantModel::create($data);
		return $save;
	}
	public function postParticipantHackathon($data,$user){
		
		$data = array(
			'event_id' => $data['event_id'],
			'employee_id' => $user->user_id,
			'email' => $user->email,
			'fullname' => $user->fullname,
			'date_of_birth' => $user->date_of_birth,
			'address' => $user->date_of_birth,
			'country' => $data['country'],
			'city' => $data['city'],
			'gender' => $user->gender,
			'university' => $data['university'],
			'major' => $data['major'],
			'semester' => $data['semester'],
			'status' => 'Approved',
			'link_drive' => $data['link_drive'],
			'modified_at' => date('Y-m-d h:i:s')
		);
		$save = EventParticipantModel::create($data);
		return $save;
	}
	public function updateHackathonfile($data,$user_id){
		return EventParticipantModel::where('event_id',$data['event_id'])->where('employee_id',$user_id)->update($data);
	}
	public function getDataHackathonData($user_id,$event_id){
		return EventParticipantModel::where('event_id',$event_id)->where('employee_id',$user_id)->first();
	}
	public function deleteHackathonData($user_id,$event_id){
		return EventParticipantModel::where('event_id',$event_id)->where('employee_id',$user_id)->delete();
	}
	public function WithdrawRewardModel($refferal_id){
		
	}
	public function postDeviceID($request, $id){
		$postParam = array('device_id' => $request['device_id']);
		return UserModels::where('user_id', $id)->update($postParam);
	}
	public function updateReferral($refferal_id){
		$postParam = array(
			'added_to_transaction_point' => 1
		);
		return ReferralModel::where('referral_id',$refferal_id)->update($postParam);
	}
	public function UpdateReferralMember($data_input, $referral_id, $filename)
    {
        $postParam = array(
            'referral_name' 		=> $data_input['referral_name'],
			'referral_email' 		=> $data_input['referral_email'],
			'referral_contact_no' 	=> $data_input['referral_contact_no'],
			'file'					=> $filename,
			'fee' 					=> $data_input['fee'],
			'job_position' 			=> $data_input['job_position'],
			'referral_employee_id' 	=> $data_input['referral_employee_id']
        );

        return ReferralModel::where('referral_id',$referral_id)->update($postParam);
    }
	public function UpdateReferralStatus($data_input, $referral_id)
	{
        $postParam = array('referral_status'=>$data_input['referral_status']);
		return ReferralModel::where('referral_id',$referral_id)->update($postParam);
	}
	public function getReferralData($id)
	{
		return ReferralModel::where('referral_id',$id)->first();
	}
	public function saveReferral($data,$user_id,$status){
		$postParam = array(
			'source' => $data['source'],
			'referral_name' => $data['referral_name'],
			'referral_email' => $data['referral_email'],
			'referral_contact_no' => $data['referral_contact_no'],
			'referral_status' => $status,  
			'referral_employee_id' => $user_id,
			'created_at' => date('Y-m-d h:i:s'),
			'modified_at' => date('Y-m-d h:i:s')
		);
		return ReferralModel::create($postParam);
	}
	//==new fase 2
	public function updateReferralfile($data){
		$postParam['file'] = $data['file'];
		return ReferralModel::where('referral_id',$data['id'])->update($postParam);
	}
	//==
	public function updateNotif($data,$notif_id,$user_id){
		return NotifModel::where('user_id',$user_id)->where('notif_id',$notif_id)->update($data);
	}
	public function saveEmployeeProjectExperience($data,$user_id){
		$postParam = array(
			'work_experience_id' => $data['work_experience_id'],
			'project_name' => $data['project_name'],
			'start_period' => $data['start_period_year'] . '-' . $data['start_period_month'] .'-'.'01',
			'end_period' => $data['end_period_year'] . '-' . $data['end_period_month'] .'-'.'01',
			'position' => $data['position'],
			'tools' => $data['tools'],
			'jobdesc' => $data['jobdesc'],
			'employee_id' => $user_id,
			'created_at' => date('Y-m-d h:i:s'),
		);	
		return EmployeeProjectExperienceModel::create($postParam);
	}
	public function updateEmployeeProjectExperience($data,$user_id){
		$postParam = array(
			'work_experience_id' => $data['work_experience_id'],
			'project_name' => $data['project_name'],
			'start_period' => $data['start_period_year'] . '-' . $data['start_period_month'] .'-'.'01',
			'end_period' => $data['end_period_year'] . '-' . $data['end_period_month'] .'-'.'01',
			'position' => $data['position'],
			'tools' => $data['tools'],
			'jobdesc' => $data['jobdesc'],
			'employee_id' => $user_id
		);	
		return EmployeeProjectExperienceModel::where('id',$data['id'])->update($postParam);
	}
	public function deleteEmployeeProjectExperience($id){
		return EmployeeProjectExperienceModel::where('id',$id)->delete();
	}
	
	public function saveEducation($data,$user_id){
		$postParam = array(
			'name' => $data['name'],
			'education_level_id' => $data['education_level_id'],
			'start_period' => $data['start_period_year'] . '-' . $data['start_period_month'] .'-'.'01',
			'end_period' => $data['end_period_year'] . '-' . $data['end_period_month'] .'-'.'01',
			'gpa' => $data['gpa'],
			'description' => $data['description'],
			'employee_id' => $user_id,
			'field_of_study' =>$data['field_of_study']
		);	
		return EmployeeQualificationModel::create($postParam);
	}
	public function updateEducation($data,$user_id){
		$postParam = array(
			'name' => $data['name'],
			'education_level_id' => $data['education_level_id'],
			'start_period' => $data['start_period_year'] . '-' . $data['start_period_month'] .'-'.'01',
			'end_period' => $data['end_period_year'] . '-' . $data['end_period_month'] .'-'.'01',
			'gpa' => $data['gpa'],
			'description' => $data['description'],
			'employee_id' => $user_id,
			'field_of_study' =>$data['field_of_study']
		);
		return EmployeeQualificationModel::where('qualification_id',$data['id'])->update($postParam);
	}
	public function deleteEmployeeEducation($id){
		return EmployeeQualificationModel::where('qualification_id',$id)->delete();
	}
	public function saveCertification($data,$user_id){
		$postParam = array(
			'employee_id' => $user_id,
			'certification_date' => $data['certification_date'],
			'title' => $data['title'],
			'description' => $data['description'],
			'created_at' => date('Y-m-d H:i:s')
		);	
		return EmployeeCertification::create($postParam);
	}
	public function updateCertification($data,$user_id){
		$postParam = array(
			'employee_id' => $user_id,
			'certification_date' => $data['certification_date'],
			'title' => $data['title'],
			'description' => $data['description']
		);	
		return EmployeeCertification::where('certification_id',$data['certification_id'])->update($postParam);
	}
	public function updateCertificationfile($data){
		$postParam['certification_file'] = $data['certification_file'];
		return EmployeeCertification::where('certification_id',$data['id'])->update($postParam);
	}
	public function deleteEmployeeCertification($id){
		return EmployeeCertification::where('certification_id',$id)->delete();
	}
	public function saveEmployeeWorkExperience($data,$user_id){
		$postParam = array(
			'company_name' => $data['company_name'],
			'start_period' => $data['start_period_year'] . '-' . $data['start_period_month'] .'-'.'01',
			'end_period' => $data['end_period_year'] . '-' . $data['end_period_month'] .'-'.'01',
			'post' => $data['post'],
			'description' => $data['description'],
			'employee_id' => $user_id,
			'created_at' => date('Y-m-d h:i:s')
		);	
		return EmployeeWorkExperienceModel::create($postParam);
	}
	public function updateEmployeeWorkExperience($data,$user_id){
		$postParam = array(
			'company_name' => $data['company_name'],
			'start_period' => $data['start_period_year'] . '-' . $data['start_period_month'] .'-'.'01',
			'end_period' => $data['end_period_year'] . '-' . $data['end_period_month'] .'-'.'01',
			'post' => $data['post'],
			'description' => $data['description'],
			'employee_id' => $user_id
		);	
		return EmployeeWorkExperienceModel::where('work_experience_id',$data['id'])->update($postParam);
	}
	public function deleteEmployeeWorkExperience($id){
		return EmployeeWorkExperienceModel::where('work_experience_id',$id)->delete();
	}

	//friend
	public function addFriend($friend_id,$user_id){
		$postParam = array(
			'uid1' => $user_id,
			'uid2' => $friend_id
		);	
		return FriendModel::create($postParam);
	}
	public function approve($friend_id,$user_id){
		$postParam = array(
			'uid1' => $user_id,
			'uid2' => $friend_id
		);	
		return FriendModel::create($postParam);
	}
	public function unFriend($friend_id,$user_id){
		FriendModel::where('uid2',$friend_id)->where('uid1',$user_id)->delete();
		return FriendModel::where('uid1',$friend_id)->where('uid2',$user_id)->delete();
	}
	public function reject($user_id,$friend_id){
		return FriendModel::where('uid1',$friend_id)->where('uid2',$user_id)->delete();
	}
	//bank account
	public function saveUserBankAccount($data_input,$user_id){
		$postParam = array(
			'employee_id' => $user_id,
			'account_name' => $data_input['account_name'],
			'account_number' => $data_input['account_number'],
			'is_primary' => $data_input['is_primary'],
			'bank_id' => $data_input['bank_id']
		);	
		return UserBankModel::create($postParam);
	}
	public function updateUserBankAccount($data_input,$user_id){
		$postParam = array(
			'employee_id' => $user_id,
			'account_name' => $data_input['account_name'],
			'account_number' => $data_input['account_number'],
			'is_primary' => $data_input['is_primary'],
			'bank_id' => $data_input['bank_id']
		);
		return UserBankModel::where('account_list_id',$data_input['account_list_id'])->update($postParam);
	}
	public function deleteUserBankAccount($id){
		return UserBankModel::where('account_list_id',$id)->delete();
	}
	//withdraw
	public function saveHistoryWithdraw($data_input){
		$postParam = array(
			'money_withdrawal' => intval($data_input['money_withdrawal']),
			'transaction_date' =>  date("Y-m-d H:i:s"),
			'transaction_status' => 'Issued',
			'transaction_note' => " ",
			'account_list_id' => $data_input['account_list_id'],
		);	
		return UserWithdrawHistoryModel::create($postParam);
	}
	public function updateWithdraw($current,$planned,$user_id){
		$postParam = array(
			'current_amount' => $current-$planned
		);
		return UserBankModel::where('user_id',$user_id)->update($postParam);
	}

	//============================== Fase 2 ==============================
	public function postComment($data,$user_id){
		$postParam = array(
			'news_id' => $data['news_id'],
			'user_id' =>  $user_id,
			'comment' => $data['comment'],
		);
		return NewsCommentModel::create($postParam);
	}
	public function postReplyComment($data,$user_id){
		$postParam = array(
			'comment_id' => $data['comment_id'],
			'comment_by' =>  $data['user_id'],
			'reply_by' =>  $user_id,
			'comment' => $data['comment']
		);		
		if(!empty($data['attachment'])){
			$postParam['attachment'] =  $data['attachment'];
		}
		if(!empty($data['desc'])){
			$postParam['desc'] =  $data['desc'];
		}
		return NewsCommentReplyModel::create($postParam);
	}
	
	public function deleteComment($id){
		return NewsCommentModel::where('comment_id',$id)->delete();
	}

	public function deleteReplyComment($id){
		return NewsCommentReplyModel::where('reply_id',$id)->delete();
	}

	public function saveCV($data,$user_id){

		EmployeeCV::where('employee_id',$user_id)->delete();

		$postParam = array(
			'desc' => $data['desc'],
			'file' => $data['file_name'],
			'employee_id' => $user_id
		);	
		return EmployeeCV::create($postParam);
	}
	public function deleteEmployeeCV($id){
		return EmployeeCV::where('employee_cv_id',$id)->delete();
	}
	public function saveEventParticipantStatus($data){
		$schedule =  EventScheduleModel::where('event_id',$data['event_id'])->get();
		if (!$schedule->isEmpty()) {
			for ($i=0; $i < count($schedule); $i++) { 
				EventParticipantStatusModel::where('employee_id',$data['user_id'])->where('schedule_id',$schedule[$i]['schedule_id'])->delete();

				$postParam = array(
					'schedule_id' => $schedule[$i]['schedule_id'],
					'employee_id' => $data['user_id'],
					'status' => 'Pending'
				);	
				 EventParticipantStatusModel::create($postParam);
			}
		}
	}

	//voting
	public function assignCandidate($data){
		$postParam = array(
			'vote_themes_id' => $data->vote_themes_id,
			'name' => $data->name,
			'icon' => $data['file_name'],
			'created_at' => date('Y-m-d h:i:s'),
		);
		return VoteChoiceModel::create($postParam);
	}
	public function updateCandidate($data, $id){
		$postParam = array(
			'vote_themes_id' => $data->vote_themes_id,
			'name' => $data->name,
			'icon' => $data['file_name'],
			'updated_at' => date('Y-m-d h:i:s'),
		);
		VoteChoiceModel::where('id', $id)->update($postParam);
		return $postParam;
	}
	public function deleteCandidate($choice_id){
		$getCandidate = VoteChoiceModel::where('id', $choice_id->id)->first();
		VoteChoiceModel::where('id', $choice_id->id)->delete();
		return $getCandidate;
	}
	public function assignVote($data, $user){
		$getCandidate = VoteChoiceModel::select('*')->where('id', $data->id)->first();
		if(empty($getCandidate)){
			return $getCandidate;
		}
		$temp = VoteChoiceSubmitModel::select('*')->where('employee_id', $user->user_id)->first();
		if(empty($temp)){
			return $temp;
		}
		if($temp['employee_id'] == $user->user_id){
			return "false";
		}
		$postParam = array(
			'vote_themes_id' => $getCandidate->vote_themes_id,
			'vote_choice_id' => $getCandidate->id,
			'employee_id' => $user->user_id,
			'created_at' => date('Y-m-d h:i:s'),
		);
		return VoteChoiceSubmitModel::create($postParam);
	}
	public function assignTheme($data){
		$postParam = array(
			'name' => $data->name,
			'banner' => $data['file_name'],
			'created_at' => date('Y-m-d h:i:s'),
		);
		return VoteThemeModel::create($postParam);
	}
	public function updateTheme($data, $theme_id){
		$postParam = array(
			'name' => $data->name,
			'banner' => $data['file_name'],
			'updated_at' => date('Y-m-d h:i:s'),
		);
		VoteThemeModel::where('id', $theme_id)->update($postParam);
		return $postParam; 
	}
	
	
	

}
