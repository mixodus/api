<?php

namespace App\Http\Controllers\API\Dashboard\MenuPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\EventModel;
use App\Models\EventScheduleModel;

class EventController extends Controller
{
    public function __construct()
    {
        $this->services = new GeneralServices();
        $this->actionServices = new ActionServices();
        $this->getDataServices = new GetDataServices();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $getEvent = $this->getDataServices->getEventList();

        if (!empty($getEvent)) {

            $action = $this->actionServices->getactionrole($checkUser->role_id, 'event');
            return $this->actionServices->response(200,"List Event",$getEvent, $action);
        }else{
            return $this->services->response(404,"News and article doesnt exist!");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $rules = [
            'company_id' => "required|integer",
            'event_type_id' => "required|integer",
            'event_title' => "required",
            'event_date' => "required||date_format:Y-m-d",
            'event_time' => "required|date_format:H:i",
            'event_note' => "required",
            'event_banner' => "required|image|mimes:jpg,png,jpeg|max:5000",
            'event_longitude' => ['regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', 'nullable'],
            'event_latitude' => ['regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', 'nullable'],
            'event_requirement' => "nullable|string",
            'event_additional_information' => "nullable|string",
            'event_prize' => "nullable|string",
            'is_road_map' => "nullable|integer"
        ];
        
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }

        $postData = ['company_id' => $request->company_id,
                     'event_type_id' => $request->event_type_id,
                     'event_title' => $request->event_title,
                     'event_date' => $request->event_date,
                     'event_time' => $request->event_time,
                     'event_note' => $request->event_note,
                     'event_longitude' => $request->event_longitude,
                     'event_latitude' => $request->event_latitude,
                     'event_charge' => $request->event_charge,
                     'event_place' => $request->event_place,
                     'event_speaker' => $request->event_speaker,
                     'event_requirement' => $request->event_requirement,
                     'event_prize' => $request->event_prize,
                     'event_additional_information' => $request->event_additional_information,
                    ];
        
        if($request->hasfile('event_banner')){
            $file = $request->file('event_banner');
            $extension = $file->getClientOriginalExtension();
            $filename = "event_".round(microtime(true)).'.'.$extension;
            $destinationPath = public_path('/uploads/event/');
            $file->move($destinationPath, $filename);

            $postData['event_banner'] = $filename;
        }

        $saved = EventModel::create($postData);

        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }
        if($request['event_type_id'] == 4){
            if(!empty($request['is_road_map'])){
                for ($i=0; $i < count($request->name); $i++) { 
                    $createSchedule['schedule_start'] = $request['schedule_start'][$i];
                    $createSchedule['schedule_end'] = $request['schedule_end'][$i];
                    $createSchedule['icon'] = $request['icon'][$i];
                    $createSchedule['name'] = $request['name'][$i];
                    $createSchedule['desc'] = $request['desc'][$i];
                    $createSchedule['link'] = $request['link'][$i];
                    $createSchedule['additional_information'] = $request['additional_information'][$i];
                    $saved2 = EventScheduleModel::create($createSchedule);
                }
            }
        }
        
        return $this->services->response(200,"Create Event success",$postData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $getEvent = $this->getDataServices->getEventDetail($request->byEventid);


        return $this->services->response(200, "Event Show", $getEvent);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $rules = [
            'company_id' => "required|integer",
            'event_type_id' => "required|integer",
            'event_title' => "required",
            'event_date' => "required||date_format:Y-m-d",
            'event_time' => "required|date_format:H:i",
            'event_note' => "required",
            'event_banner' => "image|mimes:jpg,png,jpeg|max:5000",
            'event_longitude' => ['regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', 'nullable'],
            'event_latitude' => ['regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', 'nullable']
        ];
        
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }

        $eventData = EventModel::where('event_id',$request->byEventid)->first();

        if(!$eventData){
            return $this->actionServices->response(404,"Event doesnt exist!");
        }

        $postData = ['company_id' => $request->company_id,
                     'event_type_id' => $request->event_type_id,
                     'event_title' => $request->event_title,
                     'event_date' => $request->event_date,
                     'event_time' => $request->event_time,
                     'event_note' => $request->event_note,
                     'event_longitude' => $request->event_longitude,
                     'event_latitude' => $request->event_latitude,
                     'event_charge' => $request->event_charge,
                     'event_place' => $request->event_place,
                     'event_speaker' => $request->event_speaker,
                     'event_requirement' => $request->event_requirement,
                     'event_prize' => $request->event_prize,
                     'event_additional_information' => $request->event_additional_information,
                    ];
        
        if(!empty($request->event_banner)){
            $file = $request->file('event_banner');
            $name_file = $file->getClientOriginalName();  
        }
        
        if($request->event_banner != '' && $name_file != $eventData->event_banner){
            $folder = public_path().'/uploads/event/';
            if($eventData->event_banner != '' && $eventData->event_banner != null){
                $file_old = $folder.$eventData->event_banner;
                // unlink($file_old);
            }  
            $extension = $file->getClientOriginalExtension();
            $filename = "event_".round(microtime(true)).'.'.$extension;
            $file->move($folder, $filename);

            $postData['event_banner'] = $filename;
        }
        
        if($request['event_type_id'] == 4){
            if(!empty($request['is_road_map'])){
                // EventScheduleModel::where('event_id',$request->byEventid)->delete();
                
                // $images = $request->icon_schedule_default;
                // foreach ($images as $index => $key) {
                //     $image_name       = $key;
                //     if(file_exists($key)) {
                //         $folder = public_path().'/uploads/event/hackathon/Passed/';
                //         if ($key->getClientOriginalName() != '') {
                //             $image_name = strtolower(explode(" ", $request->name[$index])[0]).'_icon_schedule.png';
                //             $key->move($folder, $image_name);
                //         }
                //     }
                // }
                $icon_schedule_default = $request->icon_schedule_default;
                for ($i=0; $i < count($icon_schedule_default); $i++) { 
                    if(file_exists($request->icon_schedule_default[$i])) {
                        if ($request->icon_schedule_default[$i]->getClientOriginalName() != '') {
                            $image_name = 'default_schedule'.time().'-'.$i  .'.'.$request->icon_schedule_default[$i]->getClientOriginalExtension();
                            $request->icon_schedule_default[$i]->move('uploads/event/hackathon/Passed/', $image_name);
                            $icon_default[] = $image_name;
                        }else{
                            $icon_default[] = $request->icon_schedule_default[$i];
                        }
                    }else{
                        $icon_default[] = $request->icon_schedule_default[$i];
                    }
                } 
                $icon_schedule_failed = $request->icon_schedule_failed;
                for ($i=0; $i < count($icon_schedule_failed); $i++) { 
                    if(file_exists($request->icon_schedule_failed[$i])) {
                        if ($request->icon_schedule_failed[$i]->getClientOriginalName() != '') {
                            $image_name = 'failed_schedule'.time().'-'.$i  .'.'.$request->icon_schedule_failed[$i]->getClientOriginalExtension();
                            $request->icon_schedule_failed[$i]->move('uploads/event/hackathon/Failed/', $image_name);
                            $icon_failed[] = $image_name;
                        }else{
                            $icon_failed[] = $request->icon_schedule_failed[$i];
                        }
                    }else{
                        $icon_failed[] = $request->icon_schedule_failed[$i];
                    }
                }
                $icon_schedule_pending = $request->icon_schedule_pending;
                for ($i=0; $i < count($icon_schedule_pending); $i++) { 
                    // $icon_pending[] = $request-> [$i];
                    if(file_exists($request->icon_schedule_pending[$i])) {
                        if ($request->icon_schedule_pending[$i]->getClientOriginalName() != '') {
                            $image_name = 'pending_schedule'.time().'-'.$i  .'.'.$request->icon_schedule_pending[$i]->getClientOriginalExtension();
                            $icon_pending[] = $image_name;
                        }else{
                            $icon_pending[] = $request->icon_schedule_pending[$i];
                        }
                    }else{
                        $icon_pending[] = $request->icon_schedule_pending[$i];
                    }
                }
                $reward_icon = $request->reward_icon;
                for ($i=0; $i < count($reward_icon); $i++) { 
                    $image_name = $request->reward_icon[$i];
                    if(file_exists($request->reward_icon[$i])) {
                        if ($request->reward_icon[$i]->getClientOriginalName() != '') {
                            $image_name = '0icon_reward'.time().'-'.$i  .'.'.$request->reward_icon[$i]->getClientOriginalExtension();
                            $request->reward_icon[$i]->move('uploads/event/', $image_name);
                        }
                    }
                    $icon[]        = $image_name;
                }
                // $images = $request->icon_schedule_failed;
                // foreach ($images as $index => $key) {
                //     $image_name       = $key;
                //     if(file_exists($key)) {
                //         $folder = public_path().'/uploads/event/hackathon/Failed/';
                //         if ($key->getClientOriginalName() != '') {
                //             $image_name = strtolower(explode(" ", $request->name[$index])[0]).'_icon_schedule.png';
                //             $key->move($folder, $image_name);
                //         }
                //     }
                // }
                // $images = $request->icon_schedule_pending;
                // foreach ($images as $index => $key) {
                //     $image_name       = $key;
                //     if(file_exists($key)) {
                //         $folder = public_path().'/uploads/event/hackathon/Pending/';
                //         if ($key->getClientOriginalName() != '') {
                //             $image_name = strtolower(explode(" ", $request->name[$index])[0]).'_icon_schedule.png';
                //             $key->move($folder, $image_name);
                //         }
                //     }
                // }
                for ($i=0; $i < count($request->name); $i++) { 
                    if($request['name'][$i]!=null){
                        $createSchedule['event_id'] = $request->byEventid;
                        $createSchedule['schedule_start'] = date("Y-m-d H:i:s", strtotime($request['schedule_start'][$i]));
                        $createSchedule['schedule_end'] =  date("Y-m-d H:i:s", strtotime($request['schedule_end'][$i]));
                        $createSchedule['icon'] = $request->icon_schedule_default[$i];
                        $createSchedule['icon_failed'] = $request->icon_schedule_failed[$i];
                        $createSchedule['icon_pending'] = $request->icon_schedule_pending[$i];
                        $createSchedule['name'] = $request['name'][$i];
                        $createSchedule['desc'] = $request['desc'][$i];
                        $createSchedule['link'] = $request['link'][$i];
                        $createSchedule['additional_information'] = $request['additional_information'][$i];
                        $saved2 = EventScheduleModel::where('schedule_id',$request['schedule_id'][$i])->update($createSchedule);
                    }
                }
            }
            
            if(count($request->reward_name)){
                for ($i=0; $i < count($request->reward_name); $i++) { 
                    if($request->reward_name[$i]!=null){
                        $rewards['name'] = $request->reward_name[$i];
                        $rewards['reward_value'] = $request->reward_value[$i];
                        $rewards['reward_icon'] = $request->reward_icon[$i];
                        $dataReward[]=$rewards;
                    }
                }
            }
            $postData['event_prize'] = json_encode($dataReward);
            $postData['event_terms_conditions'] = $request->event_terms_coditions;
        }
        $saved = EventModel::where('event_id', $request->byEventid)->update($postData); 
        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Update event success",$saved);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $eventData = EventModel::where('event_id',$request->byEventid)->first();
        if($eventData){
            if($eventData->event_banner != '' && $eventData->event_banner != null){
                $file_old = public_path().'/uploads/event/'.$eventData->event_banner;
                if(!empty($file_old)){
                    unlink($file_old);
                }
            }
        }else{
            return $this->actionServices->response(404,"Event doesnt exist!");
        }

        $delete = EventModel::where('event_id', $request->byEventid)->delete();
        if(!$delete){
			return $this->services->response(503,"Server Error!");
        }

        return $this->services->response(200,"You have been successfully delete",$delete);
    }

    public function eventType(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $getEventType = $this->getDataServices->getEventType();


        return $this->services->response(200, "List Event Type", $getEventType);
    }

    public function registerStatus(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $rules = [
            'status' => "required|in:Waiting Approval,Approved,Not Approved",
        ];
        
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }

        $saved = \DB::table('xin_events_participant')->where('id', $request->byRegisid)->update(['status' => $request->status]); 
        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Update status success");
    }

    //======================hackathon

    public function hacktown(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }

        $getEvent = $this->getDataServices->getHackTownEvent();

        $action = $this->actionServices->getactionrole($checkUser->role_id, 'hackathon-view');
        return $this->actionServices->response(200,"Hackathon Detail",$getEvent, $action);
    }
    public function hacktownParticipant(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }
        $rules = [
            'event_id' => "required|integer",
        ];
        
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
        $getEvent = $this->getDataServices->getEventParticipant($request->event_id);

        return $this->actionServices->response(200,"Hackathon Participant",$getEvent);
    }
    public function hacktownParticipantUpdate(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        if (!$checkUser) {
            return $this->actionServices->response(406, "User doesnt exist!");
        }
        $rules = [
            'event_id' => "required|integer",
            'employee_id' => "required|integer",
            'schedule_id' => "required|integer",
            'status' => "required|in:Pending,Failed,Passed",
        ];
        
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
        
        $validateDataStatus = $this->getDataServices->validateDataStatusEvent($request->all());
        if (!empty($validateDataStatus)) {
            return $this->actionServices->response(400,"You should change the failed data to change the next step status",array());
        }

        $action = $this->actionServices->hacktownParticipantUpdate($request->all());

        return $this->actionServices->response(200,"Status updated",array());
    }
}
