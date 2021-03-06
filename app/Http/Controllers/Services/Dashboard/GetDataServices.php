<?php

namespace App\Http\Controllers\Services\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Dashboard\AdminModel;
use App\Models\TransactionsPoints;
use App\Models\ResetPasswordModel;
use App\Models\ActivitiesPointModel;
use App\Models\UserModels;
use App\Models\NotifModel;
use App\Models\LevelModel;
use App\Models\AwardModel;
use App\Models\EmployeeWorkExperienceModel;
use App\Models\EmployeeQualificationModel;
use App\Models\EmployeeCertification;
use App\Models\EmployeeProjectExperienceModel;
use App\Models\EmployeeFriendshipModel;
use App\Models\EventModel;
use App\Models\EventScheduleModel;
use App\Models\EventParticipantStatusModel;
use App\Models\EventParticipantModel;
use App\Models\ChallengeModel;
use App\Models\ChallengeParticipants;
use App\Models\ChallengeQuiz;
use App\Models\NewsModel;
use App\Models\Dashboard\NewsTypeModel;
use App\Models\JobsModel;
use App\Models\JobsApplicationModel;
use App\Models\SettingModel;
use App\Models\BannerModel;
use App\Models\CountryModel;
use App\Models\BannerNewsModel;
use App\Models\ReferralModel;
use App\Models\FriendModel;
use App\Models\UserBankModel;
use App\Models\UserWithdrawModel;
use App\Models\UserWithdrawHistoryModel;
use App\Models\Dashboard\LogActivity;
use App\Models\Dashboard\EventTypeModel;
use App\Models\Fase2\LogModel;
use Firebase\JWT\JWT;
use DateTime;
use DB;

class GetDataServices extends BaseController
{
	public function __construct(){}
	// =========================================GENERAL SETTING MODULE ===============================================================
	function getSettingApp($id){
		return SettingModel::select('*')->where('setting_id',$id)->first();
	}
	function convertDateToSetting($date){
		$system_setting = $this->getSettingApp(1);
		return $d_format;
	}
	function getCountryList(){
		return CountryModel::select('*')->get();
	}
	function getProperty($object, $propertyName, $defaultValue = false)
	{
		$returnValue = $defaultValue;

		if (!empty($object)) {
			if (is_array($object)) {
				if (isset($object[$propertyName])) {
					$returnValue = $object[$propertyName];
				}
			} else {
				if (isset($object->$propertyName)) {
					$returnValue = $object->$propertyName;
				}
			}
		}

		return $returnValue;
	}
	// =========================================USER MODULE ===============================================================
	function userData($id){
		$data = AdminModel::select('user_id','email','first_name', 'gender',
		'country','profile_photo', 'zipcode','role_id')->where('user_id',$id)->first();

		$data['profile_picture_url']  = url('/')."/uploads/profile/".$data['profile_photo'];

		return $data;
	}
	function searchuserData($user_id,$keyword){
		$query = UserModels::select('user_id','email','fullname', 'date_of_birth', 'gender', 'contact_no',
		'address', 'marital_status', 'country', 'province','summary', 'job_title', 'profile_picture', 'zip_code','cash','points','skill_text','npwp')
		->where('user_id','!=',$user_id)->where('fullname','LIKE','%'.$keyword.'%');

		$data = $query->get();
		$data = $data->map(function($key) use($data){
			$key['profile_picture_url']  = url('/')."/uploads/profile/".$key['profile_picture'];
			return $key;
		});
		return $data;
	}
	function getUserDeviceToken($user_id){
		return UserModels::select('device_token')->where('user_id',$user_id)->first();
	}
	function userDatainArray($array=null,$keyword=null,$offset=null,$limit=null){
		$query = UserModels::select('user_id','email','fullname', 'date_of_birth', 'gender', 'contact_no',
		'address', 'marital_status', 'country', 'province','summary', 'job_title', 'profile_picture', 'zip_code','cash','points','skill_text','npwp');
		if($array != null){
			$query->whereIn('user_id',$array);
		}
		if($keyword != null){
			$query->where('fullname','LIKE','%'.$keyword.'%');
		}
		if($offset != null && $limit != null){
			$query->offset($offset)->limit($limit);
		}
		$data = $query->get();
		$data  = $data->map(function($key) use($array){
			$key['profile_picture_url']  = url('/')."/uploads/profile/".$key['profile_picture'];
			$key['pic']  = url('/')."/uploads/profile/".$key['profile_picture'];
			// $key['mutual_friends']  = $this->getMutualFriend($key['user_id'],$array);
			return $key;
		});
		return $data;
	}
	function userIDinArray($array=null){
		$query = UserModels::select('user_id');
		if($array != null){
			$query->whereIn('user_id',$array);
		}
		return $query->get();
	}
	public function getUserbyToken(Request $request){
		$token = $request->header('X-Token');

		$credentials = JWT::decode($token, 'X-Api-Key', array('HS256'));
		$checkAuth = UserModels::select('*')->where('user_id',$credentials->data->id)->first();
		return $checkAuth;
	}
	public function userDetail($id){
		$profile = UserModels::select('user_id','email','fullname', 'date_of_birth', 'gender', 'contact_no','address', 'marital_status', 'country', 'province','summary', 'job_title', 'profile_picture', 'zip_code','cash','points','skill_text','npwp', 'is_active')->with('work_experience','certification')->where('user_id',$id)->first();
		
		//point
		$point = $this->totalTrxPointbyUserId($id);
		$profile->points = isset($point)?$point:0;
		//friend
		$profile->friendship_status = 0;
		$profile->mutual_friends = [
			'count' => 0,
			'data' => array(),
		];
		//level
		$level = LevelModel::select('*')->where('level_min_point','<=',$profile->points)->where('level_max_point','>=',$profile->points)->first();
		$profile->level_icon_url = url('/')."/uploads/level/".$level->level_icon;
		$profile->level_name = $level->level_name;

		$profile->total_achievement =  $this->totalAwardsbyUserId($id);
		$profile->qualification  =  $this->employeeQualification($id);
		$profile->history = array([
								'event_done' =>$this->getEvent(1,$id),
								'bootcamp_done' =>$this->getEvent(2,$id),
								'challenge_done' =>$this->getChallengebyUser($id,"count")
							]);
		$profile->project = $this->getWorkExperience($id);
		//certification
		$collect = collect($profile->certifications);
		$profile->certification  = $collect->map(function($key) use($collect){
			$key['certification_file']  = url('/')."/uploads/certification/".$key['certification_file'];
			return $key;
		});

		return $profile;
	}
	// =========================================POINT MODULE ===============================================================
	public function totalTrxPointbyUserId($user_id){
		return TransactionsPoints::select('*')->where('employee_id',$user_id)->where('status',1)->sum('point');
	}
	public function getleaderboardMonth(){
		$data =  TransactionsPoints::select(DB::raw('SUM(point) AS total_point'),'xin_transaction_point.created_at','xin_transaction_point.employee_id', 'fullname' , 'profile_picture')
		->LeftJoin('xin_employees', 'xin_employees.user_id', '=', 'xin_transaction_point.employee_id')
		->where('xin_transaction_point.status',1)
		->whereYear('xin_transaction_point.created_at', date('Y'))
		->whereMonth('xin_transaction_point.created_at', date('m'))
		->groupBy('xin_transaction_point.employee_id')
		->orderBy('total_point','Desc')
		->limit(10)->get();
		$data = $data->map(function($key) use($data){
			$key['profile_picture_url']  = url('/')."/uploads/profile/".$key['profile_picture'];
			$key['month']  = $key->created_at->format('Y-m');
			return $key;
		});

		return $data;
		

		// $sql = 'SELECT sum(point) as total_point, substring(xin_transaction_point.created_at,1,7) as month , 
		// xin_transaction_point.employee_id, fullname , profile_picture, IF(profile_picture IS NULL OR profile_picture=\'\',\'\',CONCAT("'.$url_image.'",profile_picture)) as profile_picture_url   FROM `xin_transaction_point` 
		// LEFT JOIN xin_employees on (xin_transaction_point.employee_id = xin_employees.user_id) 
		// WHERE xin_transaction_point.status=1  AND substring(xin_transaction_point.created_at,1,7) = ?
		// GROUP BY employee_id , month order by total_point DESC limit 10';

		
	}

	// =========================================EMPLOYEE DETAILS MODULE ==============================================================
	public function employeeExperiences($user_id){
		return EmployeeWorkExperienceModel::select('*')->where('employee_id',$user_id)->get();
	}
	public function employeeQualification($user_id){
		$data = EmployeeQualificationModel::select('xin_employee_qualification.*','xin_qualification_education_level.name as education_level_name')->LeftJoin('xin_qualification_education_level', 'xin_qualification_education_level.education_level_id', '=', 'xin_employee_qualification.education_level_id')->where('xin_employee_qualification.employee_id',$user_id)->get();
		
		$data = $data->map(function($key){
			$key['education_level_id']  = strval($key['education_level_id']); 
			return $key;
		});
		return $data;
	}
	public function getWorkExperience($user_id){
		$data = EmployeeProjectExperienceModel::select('xin_employee_project_experiences.*','xin_employee_work_experience.company_name')->LeftJoin('xin_employee_work_experience', 'xin_employee_work_experience.work_experience_id', '=', 'xin_employee_project_experiences.work_experience_id')
					->where('xin_employee_project_experiences.employee_id',$user_id)->get();
		$data = $data->map(function($key){
			$key['work_experience_id']  = strval($key['work_experience_id']); 
			return $key;
		});
		return $data;
	}
	public function getCertification($user_id,$id=null){
		$query = EmployeeCertification::select('*')->where('employee_id',$user_id);
		if($id != null && $id !=""){
			$query->where('certification_id',$id);
		}
		$collect = $query->get();
		$collect = $collect->map(function($key) use($collect){
			$key['certification_name']  = $key['certification_file'];
			$key['certification_file']  = url('/')."/uploads/certification/".$key['certification_file'];
			return $key;
		});
		return $collect;
	}
	// =========================================NEWS MODULE ==============================================================
	public function getNews($id=null){
		$query = NewsModel::select('xin_news.*','xin_news_type.news_type_name as news_type','xin_news_type.news_colour')
				->LeftJoin('xin_news_type', 'xin_news_type.news_type_id', '=', 'xin_news.news_type_id');
				
		$data = $query->withCount('comments')->orderBy('news_id','DESC')->get();
		if(!empty($id)){
			$data = NewsModel::select('xin_news.*','xin_news_type.news_type_name as news_type','xin_news_type.news_colour')
					->LeftJoin('xin_news_type', 'xin_news_type.news_type_id', '=', 'xin_news.news_type_id')
					->where('xin_news.news_id',$id)->with('comments')->get(); 
		}
		$data = $data->map(function($key) use($data){
			$key['news_photo_url']  = url('/')."/uploads/news/".$key['news_photo'];
			return $key;
		});
		return $data;
	}

	//====NEWS FASE 2
	public function getNewsComment($data){
		return NewsCommentModel::where('news_id',$data['news_id'])->with(['user'=>function($query){
			$query->select('user_id','fullname');
		},'comment_replies'=>function($query){
			$query->with(['user'=>function($query){
				$query->select('user_id','fullname');
			}]);
		}])->get(); 
	}
	public function getNewsReplyComment($data){
		return NewsCommentReplyModel::where('comment_id',$data['comment_id'])->with(['user'=>function($query){
			$query->select('user_id','fullname');
		}])->get(); 
	}
	public function getNewsCommentDetail($id){
		return NewsCommentModel::where('comment_id',$id)->with(['user'=>function($query){
			$query->select('user_id','fullname');
		},'comment_replies'=>function($query){
			$query->with(['user'=>function($query){
				$query->select('user_id','fullname');
			}]);
		}])->first(); 
	}
	
	// =========================================Jobs MODULE ==============================================================
	public function getJobs($id=null,$user_id=null,$filtering=null){
		if(!empty($id)){
			$query = JobsModel::select('xin_jobs.*','xin_companies.name as company_name','xin_companies.logo as company_logo','xin_designations.designation_name','xin_job_type.type as job_type_name')
					->LeftJoin('xin_companies', 'xin_companies.company_id', '=', 'xin_jobs.company_id')
					->LeftJoin('xin_designations', 'xin_designations.designation_id', '=', 'xin_jobs.designation_id')
					->LeftJoin('xin_job_type', 'xin_job_type.job_type_id', '=', 'xin_jobs.job_type')
					// ->LeftJoin('provinsi', 'provinsi.id_prov', '=', 'xin_jobs.province')
					// ->LeftJoin('kabupaten', 'kabupaten.id_kab', '=', 'xin_jobs.city_id')
					// ->LeftJoin('kecamatan', 'kecamatan.id_kec', '=', 'xin_jobs.districts_id')
					// ->LeftJoin('kelurahan', 'kelurahan.id_kel', '=', 'xin_jobs.subdistrict_id')
					->where('xin_jobs.job_id',$id);
			if($user_id != null && $user_id !=""){
				$query->with(["applications" => function($q) use($user_id){
					$q->where('xin_job_applications.user_id', '=', $user_id);
				},'job_types']);
			}
			$data = $query->first();

			if(!empty($data)){
				$data['company_logo_url']  = url('/')."/uploads/company/".$data['company_logo'];
				$data->is_applied = false;
				if($data->applications != null){
					$data->is_applied = true;
				}
				$system_setting = $this->getSettingApp(1);
				$dateClose = new DateTime($data->date_of_closing);
				$data->date_of_closing = $dateClose->format($system_setting->date_format_xi);
			}
		}else{
			$query = JobsModel::select('xin_jobs.*','xin_companies.name as company_name','xin_companies.logo as company_logo')
					->with('job_types')
					->LeftJoin('xin_companies', 'xin_companies.company_id', '=', 'xin_jobs.company_id')
					// ->LeftJoin('provinsi', 'provinsi.id_prov', '=', 'xin_jobs.province')
					// ->LeftJoin('kabupaten', 'kabupaten.id_kab', '=', 'xin_jobs.city_id')
					// ->LeftJoin('kecamatan', 'kecamatan.id_kec', '=', 'xin_jobs.districts_id')
					// ->LeftJoin('kelurahan', 'kelurahan.id_kel', '=', 'xin_jobs.subdistrict_id')
					->where('xin_jobs.date_of_closing','>=',date('Y-m-d'));
			if($filtering != null){
				// if($filtering['start'] != null && $filtering['length'] !=null ){
				// 	$query->offset($start)->limit($length); 
				// }
				if($filtering['q'] != null && $filtering['q'] !="" ){
					$query->where('xin_jobs.job_title','LIKE','%'.$filtering['q'].'%');
				}
				// if($filtering['range_salary_start'] != null && $filtering['range_salary_start'] !="" ){
				// 	$query->where('xin_jobs.salary_start','>=',$filtering['range_salary_start']);
				// }
				// if($filtering['range_salary_end'] != null && $filtering['range_salary_end'] !="" ){
				// 	$query->where('xin_jobs.salary_end','<=',$filtering['range_salary_end']);
				// }
				// if($filtering['country_id'] != null && $filtering['country_id'] !="" ){
				// 	$query->where('xin_jobs.country_id',$filtering['country_id']);
				// }
				// if($filtering['province'] != null && $filtering['province'] !="" ){
				// 	$query->where('xin_jobs.province',$filtering['province']);
				// }
				// if($filtering['city_id'] != null && $filtering['city_id'] !="" ){
				// 	$query->where('xin_jobs.city_id',$filtering['city_id']);
				// }
			}
			$data = $query->orderBy('job_id','DESC')->get();
			$data = $data->map(function($key) use($data){
				$key['company_logo_url']  = url('/')."/uploads/company/".$key['company_logo'];
				return $key;
			});
		}
		return $data;
	}
	public function userJobsApplication($user_id,$job_id=null){
		$query = JobsApplicationModel::select('xin_job_applications.*','xin_jobs.job_title','xin_jobs.province','xin_jobs.country','xin_jobs.company_id','xin_jobs.designation_id','xin_companies.name as company_name','xin_companies.logo as company_logo')
					->LeftJoin('xin_jobs', 'xin_jobs.job_id', '=', 'xin_job_applications.job_id')			
					->LeftJoin('xin_companies', 'xin_companies.company_id', '=', 'xin_jobs.company_id')
					->where('xin_job_applications.user_id',$user_id);
		if($job_id != null || $job_id != ""){
			$query->where('xin_job_applications.job_id',$job_id);
		}
		$data = $query->limit(100)->orderBy('job_id','DESC')->get();

		$data = $data->map(function($key) use($data){
			$key['company_logo_url']  = url('/')."/uploads/company/".$key['company_logo'];
			return $key;
		});
		return $data;
	}
	///====Jobs Fase 2
	
	public function getJobTypeList(){
		return JobTypeModel::select('job_type_id','type')->get(); 
	}
	// =========================================EVENT MODULE ==============================================================
	public function homeEvent($user_id,$event_id=null){
		$data = null;
		$query = EventModel::select('*')->with(["participants" => function($q) use($user_id){
						$q->where('employee_id', '=', $user_id);
					}])
					->where('xin_events.event_type_id','!=',4)
					->where('xin_events.event_date','>=',date('Y-m-d'));
		if($event_id!=null){
			$query->where('xin_events.event_id',$event_id);
		}
		$data =$query->orderBy('xin_events.event_id','DESC')->limit(2)->get();

		$data = $data->map(function($key) use($data){
			$key['event_banner_url']  = url('/')."/uploads/event/".$key['event_banner'];
			$key->event_join_status ="Open";
			$key->event_is_join = false;
			if(count($key['participants'])>0 && $key['participants']!=null){
				$key->event_join_status = $key['participants'][0]->status;
				$key->event_is_join = true;
			}
			return $key;
		});
		return $data;
				
	}
	public function getBannerEvent($limit)
	{
		$query = BannerEventModel::select('*');
		if($limit != null){
			$query->limit($limit);
		}
		$data = $query->get();
		$data = $data->map(function($key) use($data){
			$key->banners_type = null;
			$key->banners_photo_url = null;
			if($key->banners_type_id == 1 ){
				$key->banners_type = "event";
				$key->banners_photo_url = url('/')."/uploads/event/".$key->banners_photo;

			}elseif($key->banners_type_id == 2 ){
				$key->banners_type = "news";
				$key->banners_photo_url =url('/')."/uploads/news/".$key->banners_photo;
			}elseif($key->banners_type_id == 3){
				$key->banners_type = "challenge";
				$key->banners_photo_url = url('/')."/uploads/challenge/".$key->banners_photo;

			}
			return $key;
		});
		return $data;
	}
	public function getBannerNews($limit)
	{
		$query = BannerNewsModel::select('xin_banners_news.*','xin_news.news_url as banner_url')->LeftJoin('xin_news', 'xin_banners_news.news_detail_id', '=', 'xin_news.news_id');
		if($limit != null){
			$query->limit($limit);
		}
		$data = $query->get();
		$data = $data->map(function($key) use($data){
			$key->banners_type = null;
			$key->banners_photo_url = url('/')."/uploads/news/".$key->news_photo;
			if($key->banners_type_id == 1 ){
				$key->banners_type = "event";
				$key->banners_photo_url = url('/')."/uploads/event/".$key->news_photo;
			}elseif($key->banners_type_id == 2 ){
				$key->banners_type = "news";
				$key->banners_photo_url =url('/')."/uploads/news/".$key->news_photo;
			}elseif($key->banners_type_id == 3){
				$key->banners_type = "challenge";
				$key->banners_photo_url = url('/')."/uploads/challenge/".$key->news_photo;
			}
			return $key;
		});
		return $data;
	}
	public function getEvent($type,$user_id){
		return EventModel::select('*')->LeftJoin('xin_events_participant', 'xin_events_participant.event_id', '=', 'xin_events.event_id')
					->where('xin_events_participant.employee_id',$user_id)
					->where('xin_events.event_date','<',date('Y-m-d'))
					->where('xin_events.event_type_id','!=',4)
					->where('xin_events.event_type_id',$type)
					->where('xin_events_participant.status','!=' ,"Waiting Approval" )
					->count();
	}
	public function validateDataStatusEvent($data){
		return EventParticipantStatusModel::where('schedule_id','<',$data['schedule_id'])->where('employee_id',$data['employee_id'])->where('status','Failed')->get();
	}
	public function getEventDetail($id){
		$data = EventModel::select('*')->with('participants'
					// ["participants" => function($q) use($user_id){
					// 	$q->where('employee_id', '=', $user_id);
					// }]
					)
					->where('xin_events.event_id',$id)
					->get();
			$data = $data->map(function($key) use($data){
						$key['event_banner_url']  = url('/')."/uploads/event/".$key['event_banner'];
						($key['event_date'] >= date('Y-m-d')) ? $key['status_event'] = 'event is comming' : $key['status_event']= "event end";
			// 			$key['event_registered'] = false;
			// 			if(count($key['participants'])>0){
			// 				$key['event_registered'] = true;
			// 				$key->status = true;
			// 				if($key['participants'][0]['status']=="Waiting Approval"){
			// 					$key->status = false;
			// 				}
			// 			}
					   	return $key;
				   	});
		return $data;
	}
	public function getEventList(){

		$data = EventModel::select('*')->with('eventType')
					->orderBy('xin_events.event_date','desc')
					->where('xin_events.event_type_id','!=',4)
					->get();
		
		$data = $data->map(function($key) use($data){
				$data = ($key['event_date'] >= date('Y-m-d')) ? $key['status_event'] = 'event is comming' : $key['status_event']= "event end";
				return $key;
		});

		return $data;
	}
	
	public function getEventParticipant($id){
		$data = EventParticipantModel::select('*')->with('scheduleStatus')->where('event_id',$id)->get();
		$data = $data->map(function($key){
			$key['idcard_file'] = url('/')."/uploads/event/hackathon/".$key['idcard_file'];
			$key['studentcard_file'] = url('/')."/uploads/event/hackathon/".$key['studentcard_file'];
			$key['transcripts_file'] = url('/')."/uploads/event/hackathon/".$key['transcripts_file'];
			$key['scheduleStatus'] = $key['scheduleStatus']->map(function($row) {
				if($row['schedule']['schedule_start'] < date('Y-m-d')){
					$row['status_timeline']= 1;
				}
				else{
					$row['status_timeline']= 2;
				}
				return $row;
			});
			return $key;
	});

	return $data;
	}
	public function getHackTownEvent(){
		$data = EventModel::select('*')->with('eventType','eventSchedules')
					->where('xin_events.event_type_id',4)
					->first();
		if($data){
			$data->makeVisible(['event_terms_conditions','event_label_terms_conditions']);
			$data->event_banner_url = url('/')."/uploads/event/".$data->event_banner;
			$data->event_prize = json_decode($data->event_prize,true);
			$data->event_prize  = collect($data->event_prize)->map(function($key) use($data){
				$key['reward_icon_url'] = url('/')."/uploads/event/".$key['reward_icon'];
				return $key;
			});
		$dataschedule= array();
			if(count($data->eventSchedules)>0){
				$data->eventSchedules->makeVisible(['icon_failed','icon_pending']);
				foreach($data->eventSchedules as $key){
					$array = $key;
					$array['icon_schedule_default']  = url('/')."/uploads/event/hackathon/Passed/".$key['icon'];
					$array['icon_schedule_failed']  = url('/')."/uploads/event/hackathon/Failed/".$key['icon_pending'];
					$array['icon_schedule_pending']  = url('/')."/uploads/event/hackathon/Pending/".$key['icon_failed'];
					$dataschedule[] = $array;
				}
			}
			$data->eventSchedules = $dataschedule;	
		}
		return $data;
	}
	// =========================================BANNER MODULE ==============================================================
	public function getHomeBanner($limit=null){
		$query = BannerModel::select('*');
		if($limit != null){
			$query->limit($limit);
		}
		$data = $query->get();
		$data = $data->map(function($key) use($data){
			$key->banners_type = null;
			$key->banners_photo_url = null;
			if($key->banners_type_id == 1 ){
				$key->banners_type = "event";
				$key->banners_photo_url = url('/')."/uploads/event/".$key->banners_photo;

			}elseif($key->banners_type_id == 2 ){
				$key->banners_type = "news";
				$key->banners_photo_url =url('/')."/uploads/news/".$key->banners_photo;
			}elseif($key->banners_type_id == 3){
				$key->banners_type = "challenge";
				$key->banners_photo_url = url('/')."/uploads/challenge/".$key->banners_photo;

			}
			return $key;
		});
		return $data;
	}
	
	// =========================================FRIEND MODULE ==============================================================
	public function get_all_friends_complete($user_id){
		$data = EmployeeFriendshipModel::select('xin_friendship.uid2','xin_friendship.uid1')
					->join('xin_friendship as b', 'b.uid1', '=', 'xin_friendship.uid2')
					->where('xin_friendship.uid1',$user_id)
					->groupBy('xin_friendship.uid2')
					->get();
		$friendIdList = array();
		$friendList = array();

		foreach ($data as $row){
			$friendIdList[] = $row['uid2'];
		}	
		$friendList = $this->userDatainArray($friendIdList);
		return $friendList;
	}
	
	public function getMutualFriend($friend_id,$user_id){
		$list2 = ($this->userDatainArray($friend_id))['data'];
		$list2 = $this->remove_element($user_id, $list2);

		$data = array();
		$data['count'] = sizeof($list2);
		$data['data'] = $list2;
		return $data;
	}

	
	public function checkFriendStatus($friend_id,$user_id){
		return FriendModel::select('*')->where('uid1',$friend_id)->where('uid2',$user_id)->get();
	}

	public function friendRequestList($user_id){
		return  DB::select('SELECT t1.uid1,xin_employees.user_id, xin_employees.fullname, xin_employees.profile_picture,  
		CONCAT("'.url('/').'/uploads/profile/'.'" ,xin_employees.profile_picture) AS profile_picture_url
							FROM xin_friendship t1 
							LEFT JOIN xin_employees ON t1.uid1 = xin_employees.user_id WHERE t1.uid2 = 9106');
	
		
	}
	// =========================================CHALLENGE MODULE ==============================================================
	public function getChallengebyUser($user_id,$type=null){
		$query = ChallengeModel::select('*')->LeftJoin('xin_challenge_participant', 'xin_challenge_participant.challenge_id', '=', 'xin_challenge.challenge_id')
					->where('xin_challenge_participant.employee_id',$user_id)
					->where('xin_challenge_participant.total_current_point','!=' ,0)
					->orderBy('xin_challenge.challenge_expired_date','ASC');
		if($type=="count"){
			$data = $query->count();
		}if($type ==null){
			$data = $query->get();
			
			$data = $data->map(function($key) use($data){
				$key['challenge_icon_trophy']  = url('/')."/uploads/challenge/".$key['challenge_icon_trophy'];
				$key['challenge_photo']  = url('/')."/uploads/challenge/".$key['challenge_photo'];
				$key->status_challenge ="";
				if($key->total_current_point != 0){
					$key->status_challenge ="Done";
				}
				return $key;
			});
		}
		return $data;
					
	}

	public function getChallenge($type, $id=null,$user_id = null,$quiz_id=null){
		$query = ChallengeModel::select('*');
		if($type =="active"){
			$query->where('xin_challenge.challenge_expired_date','>=',date('Y-m-d'));
		}elseif($type =="detail"){
			$query->where('xin_challenge.challenge_id',$id);
			$query->with(['me' => function($q) use($user_id){
				$q->where('employee_id', '=', $user_id)->limit(1);
			},'top_participant'=> function($user){
				$user->select('xin_challenge_participant.*','xin_employees.fullname','xin_employees.profile_picture AS profile_picture_url')
				->LeftJoin('xin_employees', 'xin_employees.user_id', '=', 'xin_challenge_participant.employee_id')->orderBy('total_current_point','DESC')->limit(10);
			}]);
		}elseif($type =="quiz"){
			return $query->with(['me' => function($q) use($user_id){
						$q->where('employee_id', '=', $user_id);
					},'quiz'=> function($quiz) use($quiz_id){
						$quiz->where('xin_challenge_quiz.id',$quiz_id);
					}])->join('xin_challenge_quiz', 'xin_challenge_quiz.challenge_id', '=', 'xin_challenge.challenge_id')
					->where('xin_challenge_quiz.id',$quiz_id)->first();
			
		}
		$data = $query->orderBy('xin_challenge.challenge_expired_date','ASC')->get();
		$data = $data->map(function($key) use($data){
			$key['challenge_photo']  = url('/')."/uploads/challenge/".$key['challenge_photo'];
			$key->event_category = 'Challenge';
			$key->challenge_ongoing = false;
			if($key->challenge_expired_date == date('Y-m-d')){
				$key->challenge_ongoing = true;
			}
			if(!empty($key->top_participant)){
				$participants = collect($key->top_participant);
				$user = $participants->map(function($raw) use($participants){
					$raw['profile_picture_url']  = url('/')."/uploads/profile/".$raw['profile_picture_url'];
					return $raw;
				});
			}
			return $key;
		});
		return $data;
	}
	public function checkChallengeJoin($challenge_id,$user_id){
		return ChallengeParticipants::select('*')->where('challenge_id',$challenge_id)->where('employee_id',$user_id)->first();
	}
	public function getChallengeRaw($challenge_id){
		return ChallengeModel::select('*')->where('challenge_id',$challenge_id)->first();
	}
	public function getChallengeOngoing($start=0,$end=25){
		$data = ChallengeModel::select('*')->where('challenge_expired_date','>=',date('Y-m-d'))->orderBy('challenge_expired_date', 'ASC')->get();
		$data = $data->map(function($key) use($data){
			$key['challenge_photo']  = url('/')."/uploads/challenge/".$key['challenge_photo'];
			$key->event_category = 'Challenge';
			$key->challenge_ongoing = false;
			$key->challenge_ongoing = false;
			if($key->challenge_expired_date == date('Y-m-d')){
				$date1 = str_replace('-', '/', $key->challenge_expired_date);
				$key->challenge_ongoing = true;
				$key->challenge_expired_date = date('Y-m-d',strtotime($date1 . "-1 days"));
			}
			return $key;
		});
		return $data;
	}
	public function getChallengeQuiz($challenge_id){
		return ChallengeQuiz::select('id','challenge_id','question','a','b','c')->where('challenge_id',$challenge_id)->get();
	}
	//awards
	public function getAwardsbyUserId($user_id,$offset=null,$limit=null){
		$query = AwardModel::select('xin_awards.*','xin_award_type.award_type as award_name','xin_companies.name as company_name')
				->LeftJoin('xin_award_type', 'xin_award_type.award_type_id', '=', 'xin_awards.award_type_id')
				->LeftJoin('xin_companies', 'xin_companies.company_id', '=', 'xin_awards.company_id')
				->where('xin_awards.employee_id',$user_id);
		if($offset != null && $limit !=null){
			$query->offset($offset)->limit($limit);
		}
		$data = $query->get();

		$data = $data->map(function($raw) use($data){
			$raw['award_photo']  = url('/')."/uploads/award/".$raw['award_photo'];
			return $raw;
		});
		return $data;
	}
	public function totalAwardsbyUserId($user_id){
		return AwardModel::select('*')->where('employee_id',$user_id)->count();
	}
	//Referral
	public function getReferralMember($user_id,$offset =0,$limit=25){
		return ReferralModel::select('*')->where('referral_employee_id',$user_id)->offset($offset)->limit($limit)->orderBy('referral_id', 'DESC')->get();
	}
	public function ReferralSortByStatus($status){
		if($status == null){
			$getData = ReferralModel::select('*')->with('AdminModel');
			return $getData;
		}
		if($status == "All"){
			$getData = ReferralModel::select('*')->with('AdminModel');
			return $getData;
		}
		if($status == "Pending"){
			$getData = ReferralModel::select('*')->where('referral_status','Pending')->with('AdminModel');
			return $getData;
		}
		if($status == "NotPassed"){
			$getData = ReferralModel::select('*')->where('referral_status','NotPassed')->with('AdminModel');
			return $getData;

		}
		if($status == "InReview"){
			$getData = ReferralModel::select('*')->where('referral_status','InReview')->with('AdminModel');
			return $getData;
		}
		if($status == "Passed"){
			$getData = ReferralModel::select('*')->where('referral_status','Passed')->with('AdminModel');
			return $getData;
		}
		if($status == "Complete"){
			$getData = ReferralModel::select('*')->where('referral_status','Complete')->with('AdminModel');
			return $getData;
		}
		if($status == "Paid-1"){
			$getData = ReferralModel::select('*')->where('referral_status','Paid-1')->with('AdminModel');
			return $getData;
		}
		if($status == "Paid-2"){
			$getData = ReferralModel::select('*')->where('referral_status','Paid-2')->with('AdminModel');
			return $getData;
		}
		if($status == "OnProcess"){
			$getData = ReferralModel::select('*')->where('referral_status','OnProcess')->with('AdminModel');
			return $getData;
		}
	}
	public function ValidateReferralPoints($user_id=null,$email=null){
		$query = ReferralModel::select('referral_id','withdraw_reward','referral_name as name','referral_status as status','added_to_transaction_point as added_yet');
				
		if($user_id != null && $user_id !=""){
			$query->where('referral_employee_id',$user_id)->where('referral_status', 'Successful');
		}elseif($email != null && $email !=""){
			$query->where('referral_email',$email);
		}else{
			$query->where('added_to_transaction_point',0)->where('referral_status', 'Successful');
		}
		return $query->get();
	
	}
	public function checkMemberReferral($user_id=null,$email=null){
		$query = ReferralModel::select('referral_email','referral_id','withdraw_reward','referral_name as name','referral_status as status','added_to_transaction_point as added_yet');
				
		if($email != null && $email !=""){
			$query->where('referral_email',$email);
		}
		return $query->get();
	
	}
	//point
	public function getActivityPoint($type){
		return ActivitiesPointModel::select('*')->where('activity_point_code',$type)->first();
	}
	//notif
	public function getNotif($user_id,$id=null,$nonread=null){
		$query = NotifModel::select('xin_notif.*','xin_notif_type.image_icon')
			->LeftJoin('xin_notif_type', 'xin_notif_type.notif_type_id', '=', 'xin_notif.notif_type_id')
			->where('xin_notif.user_id',$user_id);
		if($id !=null && $id !=""){
			$query->where('xin_notif.notif_id',$id);
		}
		if($nonread !=null && $nonread !=""){
			$query->where('xin_notif.is_new',FALSE);
		}
		$data = $query->orderBy('xin_notif.created_at','DESC')->get();

		$data = $data->map(function($raw) use($data){
			$raw['image_icon']  = url('/')."/uploads/notif/".$raw['image_icon'];
			$raw['is_new'] = FALSE;
			if($raw['is_new'] == 1)
				$raw['is_new'] = TRUE;
			$date = $raw->created_at;
			$date_only = substr($date, 0,10 );
			$raw->date_past = $date;
			$raw->date_convert = TRUE;

			if($date_only == date('Y-m-d'))
				$date_past = $this->time_elapsed_string($date);
				$minute = date('H:i:s');
				$raw->date_past = $this->time_elapsed_string($date);
				$raw->date_convert = FALSE;
			
			return $raw;
		});
		return $data;
	}

	//level
	public function getLevel(){
		$data = LevelModel::select('*')->orderBy('level_max_point','DESC')->get();
		$data = $data->map(function($raw) use($data){
			$raw['level_icon_url']  = url('/')."/uploads/level/".$raw['level_icon'];
			return $raw;
		});
		return $data;
	}
	//bank account
	public function getUserBankAccount($user_id){
		$data = UserBankModel::select('*')->where('employee_id',$user_id)->get();
		$data = $data->map(function($raw) use($data){
			$raw['user_id'] = $raw->employee_id;
			$raw['primary_account'] = "No";
			if($raw->isPrimary = 1)
				$raw['primary_account'] = "Yes";
			
			return $raw;
		});
		return $data;
	}
	//withdraw
	public function getWithdrawInfo($user_id){
		// $data = DB::select('SELECT SUM(withdraw_reward) FROM xin_referral WHERE user_id = '.$user_id.' AND added_to_transaction_point IS NOT NULL');
		// return $data;
		// $postParam = array(
		//     'total_amount' => $data
		// );
		// UserWithdrawModel::where('user_id',$user_id)->update($postParam);

		// DB::update('UPDATE xin_withdraw SET total_amount = (SELECT SUM(withdraw_reward) FROM xin_referral WHERE user_id = ? AND added_to_transaction_point IS NOT NULL', [$user_id]);
		return UserWithdrawModel::select('*')->where('user_id',$user_id)->get();
	}
	public function getWithdrawCurrentValue($user_id){
		return UserWithdrawModel::select(DB::raw('SUM(current_amount) as current_amounts'))->where('user_id',$user_id)
		->get();
	}
	public function getWithdrawHistory($user_id){
		$id = 6;
		return DB::select('SELECT *,withdraw_history_id as id from xin_withdraw_history JOIN xin_employee_bank_account ON xin_withdraw_history.account_list_id = xin_employee_bank_account.account_list_id WHERE xin_employee_bank_account.account_list_id = '.$id);
	}
	public function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	private function remove_element($element, $theList){
		$result = [];

		if (sizeof($theList) == 0){
			$result = [];
		}
		else {
			$result = $theList;
			if (($key = array_search($element, $result)) !== false) {
				unset($result[$key]);
			}
		}
		return $result;
	}
	
//================================Dashboard=======================================//
	public function getAdminbyToken(Request $request){
		$token = $request->header('X-Token');

		$credentials = JWT::decode($token, 'X-Api-Key', array('HS256'));
		$checkAuth = AdminModel::select('*')->where('user_id',$credentials->data->id)->first();
		return $checkAuth;
	}

	public function employeeLevellist(){
		$profile = UserModels::select('user_id','email','fullname')->get();
		
		foreach($profile as $profiles){
			$point = $this->totalTrxPointbyUserId($profiles->user_id);
			$profile->points = isset($point)?$point:0;
			
			$profiles->point = $point;

			$level = LevelModel::select('*')->where('level_min_point','<=',$point)->where('level_max_point','>=',$point)->first();
			$profiles->level = $level;
		}

		return $profile;
	}

	public function employeeLevelDetail($id){
		$profile = UserModels::select('user_id','email','fullname')->with('trx_points')->where('user_id', $id)->get();
		
		foreach($profile as $profiles){
			$point = $this->totalTrxPointbyUserId($profiles->user_id);
			$profile->points = isset($point)?$point:0;
			$profiles->point = $point;

			$level = LevelModel::select('*')->where('level_min_point','<=',$point)->where('level_max_point','>=',$point)->first();
			$profiles->level_icon_url = url('/')."/uploads/level/".$level->level_icon;
			$profiles->level = $level;
		}

		return $profile;
	}

	public function getNewsType(){

		return NewsTypeModel::select('news_type_id', 'news_type_name')->get();
	}

	public function dashboardLog($id=null){

		$query = LogActivity::select('*');

		if(!empty($id)){
			$query->where('id', $id);
		}

		$data = $query->get();
		return $data;
	}

	public function mobileLog($id=null){

		$query = LogModel::select('*');

		if(!empty($id)){
			$query->where('id', $id);
		}

		$data = $query->get();
		return $data;
	}

	public function getEventType(){

		return EventTypeModel::select('event_type_id', 'event_type_name')->get();
	}
}
