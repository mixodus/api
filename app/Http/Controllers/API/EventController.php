<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\EventParticipantModel;
use App\Models\EventModel;
use Exception;

class EventController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->eventModel = EventModel::select('*');
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
		$getEvent = $this->getDataServices->getEventList();	

		
		return $this->services->response(200,"List Event",array('ongoing'=>$getEvent));
	}
	public function EventType($id) {
		$getEvent = EventModel::where('event_type_id', $id)->get();

		if (!$getEvent->isEmpty()) {
			$getEvent = $getEvent->map(function($key) use($getEvent){
				$key['event_banner_url']  = url('/')."/uploads/event/".$key['event_banner_url'];
					if($key['event_type_id']== 1){
						$key['event_category'] = "Event";
					}else if($key['event_type_id']== 2){
						$key['event_category'] = "Bootcamp";
					}
					$today = date('Y-m-d');
					$timeToday = date('H:i');
					$key->event_ongoing = false;
					$key->event_joinable = false;
					if($key->event_date > $today){
						$key->event_ongoing = false;
						$key->event_joinable = true;
	
					}else if($key->event_date == $today){
						$time = strtotime($key->event_time) - 60*60;
						$getTime = date('H:i',$time);
						$key->event_ongoing = true;
						$key->event_joinable = true;
						if($timeToday > $getTime){
							$key->event_joinable = false;
						}
					}
				return $key;
			});
			return $this->services->response(200,"Event Type",$getEvent);
		}else{
			return $this->services->response(200,"Event Type tidak ditemukan!",array());
		}
	}

	public function joinEvent(Request $request){
		$rules = [
			'event_id' => "required|integer",
			'email' => "required|string",
			'fullname' => "required|string",
			'date_of_birth' => "required|string",
			'country' => "required|string",
			'city' => "required|string",
			'gender' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$eventDetail = $this->getDataServices->homeEvent($checkUser->user_id,$request->event_id);
		if(count($eventDetail)==0){
			return $this->services->response(400,"Tanggal acara telah kedaluwarsa.",array());
		}
		if($eventDetail[0]['event_is_join']== true){
			return $this->services->response(402,"Anda telah terdaftar pada acara ini.");
		}
		
		$postParticipants = $this->actionServices->postParticipantEvent($request->all(),$checkUser->user_id);
		//notif
		$save_notif = $this->actionServices->postNotif(1,$request->event_id,$checkUser->user_id,'Anda telah terdaftar pada' .$eventDetail[0]['event_title']. ' dan sedang menunggu konfirmasi.');
		if(!$postParticipants){
			return $this->services->response(400,"Kesalahan server, tolong hubungi admin !");
		}
		return $this->services->response(200,"Pendaftaran berhasil!", $request->all());
	}
	public function detail(Request $request,$id){
		
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$eventDetail = $this->getDataServices->getEventDetail($checkUser->user_id,$id);
		if (!$eventDetail->isEmpty()) {
			return $this->services->response(200,"Detail",$eventDetail);
		}else{
			return $this->services->response(200,"Data tidak ditemukan!",array());
		}
	}
	public function HistoryEvent(Request $request,$id){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->HistoryEvent($checkUser->user_id,$id);
		
		return $this->services->response(200,"Daftar event yang sudah diikuti",$data);
	}
	// ================================================
	public function HackathonSemester(){
		$data['format1'] = array(1,2,3,4,5,6,7,8);
		// for ($i=1; $i <= 8; $i++) { 
		// 	$semester['semester'] = $i;
		// 	$array[] = $semester;
		// }
		// $data['format2'] = $array;
		$data['upload_icon_url'] = url('/')."/uploads/event/hackathon/general/upload.png";
		return $this->services->response(200,"Semester Data",$data);
	}
	// Hackathon
	public function Hackathon(Request $request) {
		$checkUser = $this->getDataServices->getUserbyToken($request);
		if(empty($checkUser)){
			$checkUser = [
				'user_id' => 0
			];
		}
		$getEvent = EventModel::where('event_type_id','=', '4')->with(["eventSchedules","participants" => function($q) use($checkUser){
				$q->where('employee_id', '=', $checkUser->user_id);
			}])->get();
			$getEvent = $getEvent->map(function($key) use($getEvent,$checkUser){
				$key['label_description'] = "Deskripsi";
				$key['label_requirements'] = "Persyaratan";
				$key['label_additional_information'] = "Informasi Lainnya";
				$key['event_prize'] = json_decode($key['event_prize'],true);
				$key['event_prize'] = collect($key['event_prize'])->map(function($raw){
					$raw['reward_icon_url']  = url('/')."/uploads/event/".$raw['reward_icon'];
					return $raw;
				});
				$key['eventSchedules'] = collect($key['eventSchedules'])->map(function($row) use($checkUser){
					$row['schedule_start'] = $this->getDataServices->tgl_indov2(date("d-m-Y", strtotime($row['schedule_start'])));
					$today = date('Y-m-d');
					$today=date('Y-m-d', strtotime($today));

					$stratDate = date('Y-m-d', strtotime($row['schedule_start']));
					$endDate = date('Y-m-d', strtotime($row['schedule_end']));
					$getStatusState =  $this->getDataServices->checkEventScheduleStatusState($row['schedule_id'],$checkUser->user_id);
					
					$row['status']  = "Pending";
					if($getStatusState!=null){
						$row['status']  = $getStatusState->status;
					}
					$getStatus =  $this->getDataServices->checkEventScheduleStatus($row['schedule_id'],$checkUser->user_id);
					$getPassedStatus =  $this->getDataServices->checkEventPassedScheduleStatus($row['schedule_id'],$checkUser->user_id);
					$getCurrentState =  $this->getDataServices->geteventCurrentState($row['schedule_id']);
					$getFailedState =  $this->getDataServices->checkEventScheduleStatus($row['schedule_id'],$checkUser->user_id);
					
					$row['icon_status_url']  = url('/')."/uploads/event/hackathon/".$row['status']."/".$row['icon'];
				
					if($getCurrentState!=null){
						$row['is_current_state'] = true;
						$row['is_current_state_backend'] = "true";
						$row['icon_status_url']  = url('/')."/uploads/event/hackathon/Passed/".$row['icon'];
					}else{
						$row['is_current_state'] = false;
						$row['is_current_state_backend'] = "false";
					}
					if($getStatus!=null){
						$row['is_current_state'] = true;
						$row['is_current_state_backend'] = "true";
					}
					
					$getNextSchedule =  $this->getDataServices->getNextSchedule($row['schedule_id']);
					$row['next_schedule_message'] = "";
					$row['next_schedule_date'] = "";
					if($getNextSchedule!=null){
						$row['next_schedule_message'] = "Jadwal berikutnya ".$getNextSchedule->name;
						$row['next_schedule_date'] = $this->getDataServices->tgl_indov2(date("d-m-Y", strtotime($getNextSchedule->schedule_start)));
					}
					$row['icon_url']  = url('/')."/uploads/event/hackathon/Passed/".$row['icon'];
					return $row;
				});
				$array = $key['eventSchedules']->toArray();
				$arrayTotal[] = array();
				for ($i=0; $i < count($array); $i++) {
					if($array[$i]['is_current_state'] === "true"){
						$arrayTotal[] = "exist";
					}	
				}
				if(count(array_filter($arrayTotal))==0){
					$getCurrentStateBefore =  $this->getDataServices->getCurrentStateBefore($getEvent[0]['event_id'],$checkUser->user_id);
				
					if($getCurrentStateBefore){
						$valueArray = intval($getCurrentStateBefore['schedule_id'])-1;
						$key['eventSchedules'][$valueArray]['is_current_state'] = true;
					}else{
						$key['eventSchedules'][0]['is_current_state'] = true;
					}
				}
				$failedData = $this->getDataServices->getFailedStatusEvent($getEvent[0]['event_id'],$checkUser->user_id);
				$getAllState =  $this->getDataServices->getAllStatusEvent($getEvent[0]['event_id'],$checkUser->user_id);
				if ($getAllState->isEmpty()) {
					$key['eventSchedules'][0]['is_current_state'] = true;
					$key['eventSchedules'][0]['is_current_state_backend'] = "true";
				}
					
				$key['failed_message'] = "";
				if($failedData){
					$key['current_state'] = $failedData;
					$key['failed_message'] = "Maaf, Anda tidak lolos ke tahap berikutnya karena belum memenuhi kualifikasi yang tersedia. Terima kasih telah berpartisipasi.";
				}else{

					$key['current_state'] = null;
				}
				$key['event_banner_url']  = url('/')."/uploads/event/".$key['event_banner'];
				$key['event_category'] = "Hackathon";
				$key->event_ongoing = true;
				$key->event_joinable = true;
				$key->button_wording = "Daftar Hackathon";
				
				$key->event_coming_soon = false;
				$key->event_coming_soon_message = "Pendaftaran dibuka tanggal ".$key['eventSchedules'][0]['schedule_start'];
				$key->event_coming_soon_title = "Segera Hadir";
				if(count($key['participants'])>0){
					$key->event_joinable = false;
					$key->button_wording = "Cek Status Anda";
					if($key['participants'][0]['idcard_file'] == null || $key['participants'][0]['studentcard_file']==null || $key['participants'][0]['transcripts_file']==null)
					{
						$deletData = $this->deleteHackathonData($checkUser->user_id,$getEvent[0]['event_id']);
						$key->event_joinable = true;
						$key->button_wording = "Daftar Hackathon";
					}	
				}
				return $key;
			});
		if (!$getEvent->isEmpty()) {
			$getEvent = $getEvent[0];
		}
		return $this->services->response(200,"Event Hackathon",$getEvent);
	}
	public function HackathonTerms(Request $request) {
		$getEvent = EventModel::select('event_terms_conditions','event_label_terms_conditions')->where('event_type_id', '4')->first();
		$getEvent->makeVisible(['event_terms_conditions','event_label_terms_conditions']);
		return $this->services->response(200,"Event Hackathon Terms and Conditions",$getEvent);
	}
	public function RegisterHackathon(Request $request){
		$rules = [
			'event_id' => "required|integer",
			'university' => "required|string",
			'major' => "required|string",
			'semester' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$checkIsJoin = $this->getDataServices->getEventParticipantbyUser($request->event_id,$checkUser->user_id);
		if(!empty($checkIsJoin)){
			return $this->services->response(402,"Anda telah terdaftar pada event ini.");
		}
		$postParticipants = $this->actionServices->postParticipantHackathon($request->all(),$checkUser);
		$request['user_id'] = $checkUser->user_id;
		$EventParticipantStatus = $this->actionServices->saveEventParticipantStatus($request->all());
		if(!$postParticipants){
			return $this->services->response(400,"Jaringan bermasalah!");
		}
		return $this->services->response(200,"Pendaftaran berhasil!", $request->all());
	}
	public function ResetRegisterHackathon(Request $request){
		$rules = [
			'event_id' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		
		$deletData = $this->deleteHackathonData($checkUser->user_id,$request->event_id);
		return $this->services->response(200,"Pendaftaran berhasil direset!", $request->all());
	}
	public function HackathonUploadFile(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);

		
		$rules = [
			'event_id' => "required|integer",
			'type' => "required|in:1,2,3,4",
			'file' => "required|max:5121"
		];
		$image = $request->file('file');
		if(!$request->hasFile('file')){ 
			return $this->services->response(406,"File ".$message." tidak ditemukan");
		}	
		if($request->type =="1"){
			$message = "KTP";
		}
		if($request->type =="2"){
			$message = "Kartu Mahasiswa";
		}
		if($request->type =="3"){
			$message = "Transkrip Nilai";
		}
		if($request->type =="4"){
			$message = "CV";
		}
		$checkValidate = $this->services->validate2($request->all(),$rules);
		if(!empty($checkValidate)){
			$deletData = $this->deleteHackathonData($checkUser->user_id,$request->event_id);
			return $this->services->response(406,$checkValidate);
		}	
		if($request->type =="1"){
			$imgname = "Hackathon_IDCARD_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['idcard_file'] = $imgname;
			$message = "KTP";
		}
		if($request->type =="2"){
			$imgname = "Hackathon_STUDENTCARD_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['studentcard_file'] = $imgname;
			$message = "Kartu Mahasiswa";
		}
		if($request->type =="3"){
			$imgname = "Hackathon_STUDENTTRANSCRIPT_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['transcripts_file'] = $imgname;
			$message = "Transkrip Nilai";
		}
		if($request->type =="4"){
			$imgname = "Hackathon_CV_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['cv_file'] = $imgname;
			$message = "CV";
		}
		$destinationPath = public_path('/uploads/event/hackathon/');
		// try {
		$upload = $image->move($destinationPath, $imgname);
		// } catch (Exception $e) {
		// 	return $this->services->response(406,"Terjadi Kesalahan Dalam Proses Upload!");
		// } catch (\Throwable $e) {

		// }
		
		$postData['employee_id'] = $checkUser->user_id;
		$postData['event_id'] = $request->event_id;
		
		$upload = $this->actionServices->updateHackathonfile($postData,$checkUser->user_id);
		if(!$upload){
			$deletData = $this->deleteHackathonData($checkUser->user_id,$request->event_id);
		} 
		return $this->services->response(200,"File berhasil ".$message." diunggah",$request->all());
	}
	public function deleteHackathonData($user_id,$event_id){
		$checkData = $this->actionServices->getDataHackathonData($user_id,$event_id);
		if($checkData!=null){
			if($checkData['idcardfile'] != "" && !empty($checkData['idcardfile'])){
				unlink(public_path('uploads/event/hackathon/'.$checkData['idcardfile']));
			}
			if($checkData['studentcard_file'] != "" && !empty($checkData['studentcard_file'])){
				unlink(public_path('uploads/event/hackathon/'.$checkData['studentcard_file']));
			}
			if($checkData['transcripts_file'] != "" && !empty($checkData['transcripts_file'])){
				unlink(public_path('uploads/event/hackathon/'.$checkData['transcripts_file']));
			}
		}
		$deletedata = $this->actionServices->deleteHackathonData($user_id,$event_id);
	}
	public function searcharray($value, $key, $array) {
		foreach ($array as $k => $val) {
			if ($val[$key] == $value) {
				return $value;
			}
		}
		return null;
	 }
}
