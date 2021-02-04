<?php

namespace App\Http\Controllers\API\Dashboard\MenuPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\EventModel;

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
            'event_latitude' => ['regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', 'nullable']
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
                     'event_speaker' => $request->event_speaker
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
                     'event_speaker' => $request->event_speaker
                    ];
        
        if(!empty($request->event_banner)){
            $file = $request->file('event_banner');
            $name_file = $file->getClientOriginalName();  
        }

        if($request->event_banner != '' && $name_file != $eventData->event_banner){
            $folder = public_path().'/uploads/event/';

            if($eventData->event_banner != '' && $eventData->event_banner != null){
                $file_old = $folder.$eventData->event_banner;
                unlink($file_old);
            }  
            $extension = $file->getClientOriginalExtension();
            $filename = "event_".round(microtime(true)).'.'.$extension;
            $file->move($folder, $filename);

            $postData['event_banner'] = $filename;
        }

        $saved = EventModel::where('event_id', $request->byEventid)->update($postData); 
        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Create news success",$postData);
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
}