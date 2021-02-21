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
		for ($i=1; $i <= 8; $i++) { 
			$semester['semester'] = $i;
			$array[] = $semester;
		}
		$data['format2'] = $array;
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
		$getEvent = EventModel::where('event_type_id', '4')->with(["eventSchedules","participants" => function($q) use($checkUser){
				$q->where('employee_id', '=', $checkUser->user_id);
			}])->get();
			$getEvent = $getEvent->map(function($key) use($getEvent,$checkUser){
				
				$key['event_prize'] = json_decode($key['event_prize'],true);
				$key['event_prize'] = collect($key['event_prize'])->map(function($raw){
					$raw['reward_icon_url']  = url('/')."/uploads/event/".$raw['reward_icon'];
					return $raw;
				});
				$key['eventSchedules'] = collect($key['eventSchedules'])->map(function($row) use($checkUser){
					$getStatus =  $this->getDataServices->checkEventScheduleStatus($row['schedule_id'],$checkUser->user_id);
					
					$row['status']  = "Pending"; 
					if($getStatus!=null){
						$row['status']  = $getStatus->status;
					}
					return $row;
				});
				$key['event_banner_url']  = url('/')."/uploads/event/".$key['event_banner_url'];
				$key['event_category'] = "Hackathon";
				$key->event_ongoing = true;
				$key->event_joinable = false;
				if(count($key['participants'])==0){
					$key->event_joinable = true;
				}
				return $key;
			});
		if (!$getEvent->isEmpty()) {
			$getEvent = $getEvent[0];
		}
		return $this->services->response(200,"Event Hackathon",$getEvent);
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
		if(!$postParticipants){
			return $this->services->response(400,"Jaringan bermasalah!");
		}
		return $this->services->response(200,"Pendaftaran berhasil!", $request->all());
	}
	public function HackathonUploadFile(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);

		$rules = [
			'event_id' => "required|integer",
			'type' => "required|string|in:1,2,3",
			'file' => "required|mimes:jpg,png,jpeg|max:50240",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			$deletData = $this->deleteHackathonData($checkUser->user_id,$request->event_id);
			return $checkValidate;
		}		
		$image = $request->file('file');
		if($request->type =="1"){
			$imgname = "Hackathon_IDCARD_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['idcard_file'] = $imgname;
		}
		if($request->type =="2"){
			$imgname = "Hackathon_STUDENTCARD_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['studentcard_file'] = $imgname;
		}
		if($request->type =="3"){
			$imgname = "Hackathon_STUDENTTRANSCRIPT_".round(microtime(true)).'.'.$image->getClientOriginalExtension();
			$postData['transcripts_file'] = $imgname;
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
		return $this->services->response(200,"File berhasil diunggah",$request->all());
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
}
