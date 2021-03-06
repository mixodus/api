<?php

namespace App\Http\Controllers\Services\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\Dashboard\PermissionsModel;
use App\Models\RolesModel;
use App\Models\JobsModel;
use App\Models\ReferralModel;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivitiesPointModel;
use App\Models\EventModel;
use App\Models\EventScheduleModel;
use App\Models\EventParticipantStatusModel;
use App\Models\EventParticipantModel;
use App\Models\NotifModel;

class ActionServices extends Controller
{
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
    public function __construct(){
		$this->services = new GeneralServices();
    }
    public function hacktownParticipantUpdate($data){
        $updateData['status']= $data['status'];
		return EventParticipantStatusModel::where('schedule_id',$data['schedule_id'])->where('employee_id',$data['employee_id'])->update($updateData);
	}
    
    public function getactionrole($role_id, $name_action, $data=null)
    {
        $getaction = PermissionsModel::select('xin_permissions.action')
                        ->join('xin_roles_permissions', 'xin_roles_permissions.permission_id', '=', 'xin_permissions.id')
                        ->where('xin_permissions.name', 'like','%'.$name_action.'%')
                        ->where('xin_roles_permissions.role_id', $role_id)
                        ->get()->toArray();
       
        foreach($getaction as $getactions){
            $data[]= $getactions['action'];
        }
        
        if(empty($data))
        {
            return response($data);
        }
        return response($data);
        
    }

    public function postJobs($data_input)
    {
        $postParam = array(
            'job_id' => $this->services->randomid(4),
            'company_id' => $data_input['company_id'],
            'job_title' => $data_input['job_title'],
            'designation_id' => $data_input['designation_id'],
            'job_type' => $data_input['job_type'],
            'job_vacancy' => $data_input['job_vacancy'],
            'gender' => $data_input['gender'],
            'minimum_experience' => $data_input['minimum_experience'],
            'date_of_closing' => $data_input['date_of_closing'],
            'short_description' => $data_input['short_description'],
            'long_description' => $data_input['long_description'],
            'status' => $data_input['status'],
            'country' => $data_input['country'],
            'province' => $data_input['province'],
            'city_id' => $data_input['city_id'],
            'districts_id' => $data_input['districts_id'],
            'subdistrict_id' => $data_input['subdistrict_id'],
            'currency_id' => $data_input['currency_id'],
            'salary_desc' => $data_input['salary_desc'],
            'salary_start' => $data_input['salary_start'],
            'salary_end' => $data_input['salary_end'],
        );
        
        return JobsModel::create($postParam);
    }

    public function UpdateJobs($data_input, $jobs_id)
    {
        $postParam = array(
            'company_id' => $data_input['company_id'],
            'job_title' => $data_input['job_title'],
            'designation_id' => $data_input['designation_id'],
            'job_type' => $data_input['job_type'],
            'job_vacancy' => $data_input['job_vacancy'],
            'gender' => $data_input['gender'],
            'minimum_experience' => $data_input['minimum_experience'],
            'date_of_closing' => $data_input['date_of_closing'],
            'short_description' => $data_input['short_description'],
            'long_description' => $data_input['long_description'],
            'status' => $data_input['status'],
            'country' => $data_input['country'],
            'province' => $data_input['province'],
            'city_id' => $data_input['city_id'],
            'districts_id' => $data_input['districts_id'],
            'subdistrict_id' => $data_input['subdistrict_id'],
            'currency_id' => $data_input['currency_id'],
            'salary_desc' => $data_input['salary_desc'],
            'salary_start' => $data_input['salary_start'],
            'salary_end' => $data_input['salary_end'],
        );
        
        return JobsModel::where('job_id',$jobs_id)->update($postParam);
    }
    
    

    public function deleteJobs($jobs_id)
    {        
        return JobsModel::where('job_id',$jobs_id)->delete();
    }

    public function getActivity(){
        return ActivitiesPointModel::all();
    }

    public function response($statusCode, $msg, $data=null,$with_alert= null){
		
		$response = [
			'status' => true,
			'message' => $msg,
			'data' => $data,
			'action' => $with_alert,
		];
		if ($statusCode != 200) {
			$response = [
				'status' => false,
				'message' => $msg
			];
		}
		if ($with_alert != null && $statusCode != 200) {
			$response = [
                'status' 	=> false,
                'alert' 	=> 'failed',
                'message' 	=> $msg
			];
			return response()->json($response, 200);
		}
		if ($with_alert != null && $statusCode != 200) {
			$response = [
                'status' 	=> true,
                'alert' 	=> 'success',
                'message' 	=> $msg
			];
		}
		return response()->json($response, $statusCode);
	}

    //referral action services
    public function updateReferral($refferal_id){
		$postParam = array(
			'added_to_transaction_point' => 1
		);
		return ReferralModel::where('referral_id',$refferal_id)->update($postParam);
	}
	public function AdminUpdateReferralMember($data_input, $referral_id, $filename)
    {
        $postParam = array(
            'referral_name' 		=> $data_input['referral_name'],
			'referral_email' 		=> $data_input['referral_email'],
			'referral_contact_no' 	=> $data_input['referral_contact_no'],
			'file'					=> $filename,
			'fee' 					=> $data_input['fee'],
			'job_position' 			=> $data_input['job_position'],
        );

        return ReferralModel::where('referral_id',$referral_id)->update($postParam);
    }
    public function UpdateReferralMember($data_input, $referral_id, $filename)
    {
        $postParam = array(
            'referral_name' 		=> $data_input['referral_name'],
			'referral_email' 		=> $data_input['referral_email'],
			'referral_contact_no' 	=> $data_input['referral_contact_no'],
			'file'					=> $filename,
			'job_position' 			=> $data_input['job_position'],
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
}
