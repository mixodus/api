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
use App\Models\EmployeeWorkExperienceModel;
use App\Models\EmployeeQualificationModel;
use App\Models\EmployeeProjectExperienceModel;
use App\Models\ChallengeParticipants;
use App\Models\JobsApplicationModel;
use App\Models\WithdrawRewardModel;
use App\Models\ReferralModel;
use Firebase\JWT\JWT;

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
			'status' => 'Waiting Approval',
			'modified_at' => date('Y-m-d h:i:s')
		);
		$save = EventParticipantModel::create($data);
		return $save;
	}
	public function joinChallenge($data,$user_id){
		$postParam = array(
			'challenge_id' => $data->challenge_id,
			'employee_id' => $user_id,
			'total_point' => $data->challenge_total_task * $data->challenge_point_every_task,
			'total_current_point' =>0,
			'total_current_task' => 0, 
			'total_task'=>$data->challenge_total_task,
			'created_at' => date('Y-m-d h:i:s'),
			'modified_at' => date('Y-m-d h:i:s')
		);
		return ChallengeParticipants::create($postParam);
	}
	public function WithdrawRewardModel($refferal_id){
		
	}
	public function updateReferral($refferal_id){
		$postParam = array(
			'added_to_transaction_point' => 1
		);
		return ReferralModel::where('referral_id',$refferal_id)->update($postParam);
	}
	public function saveReferral($data,$user_id,$status){
		$postParam = array(
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

	

}
