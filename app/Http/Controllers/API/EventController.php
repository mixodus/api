<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\EventParticipantModel;
use App\Models\EventModel;

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

		if (!$getEvent->isEmpty()) {
			return $this->services->response(200,"List Event",array('ongoing'=>$getEvent));
		}else{
			return $this->services->response(200,"Event doesnt exist!",array());
        }
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
			return $this->services->response(200,"Event Type Doesnt Exists!",array());
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
			return $this->services->response(400,"Event Date is already expired !",array());
		}
		if($eventDetail[0]['event_is_join']== true){
			return $this->services->response(402,"Sorry, already registered to this event");
		}
		
		$postParticipants = $this->actionServices->postParticipantEvent($request->all(),$checkUser->user_id);
		//notif
		$save_notif = $this->actionServices->postNotif(1,$request->event_id,$checkUser->user_id,'You are registered to ' .$eventDetail[0]['event_title']. ' and Waiting for Aprroval');
		if(!$postParticipants){
			return $this->services->response(400,"Any problem with server. Please contact admin !");
		}
		return $this->services->response(200,"You are successfully registered to this event", $request->all());
	}
	public function detail(Request $request,$id){
		
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$eventDetail = $this->getDataServices->getEventDetail($checkUser->user_id,$id);
		if (!$eventDetail->isEmpty()) {
			return $this->services->response(200,"Details",$eventDetail);
		}else{
			return $this->services->response(200,"Event Not Found!",array());
		}
	}
	public function HistoryEvent(Request $request,$id){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$data = $this->getDataServices->HistoryEvent($checkUser->user_id,$id);
		
		return $this->services->response(200,"List Events Done",$data);
    }
}
