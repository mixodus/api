<?php

namespace App\Http\Controllers\API\Dashboard\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\Dashboard\AdminModel;
use App\Models\UserModels;
use App\Models\TransactionsPoints;
use App\Models\ActivitiesPointModel;

class LevelController extends Controller
{
    public function __construct(){
        $this->getDataServices = new GetDataServices();
        $this->services = new GeneralServices();
        $this->users = AdminModel::where('is_active', 1);
        $this->getAction = new ActionServices();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->getAction->response(406,"User doesnt exist!");
        }

        $profile = $this->getDataServices->employeeLevellist();
        return $this->getAction->response(200, "Employee Level List", $profile);
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

		if (!$checkUser){
            return $this->getAction->response(406,"User doesnt exist!");
        }

        $activity_point = ActivitiesPointModel::where('activity_point_id', $request->activity_point_id)->first();
        
        $PostRequest = array(
            'point_type' => 1,
            'activity_point_code' => $activity_point->activity_point_code,
            'point' => $activity_point->activity_point_point,
            'employee_id' => $request->employee_id,
            'challenge_id' => (!empty($request->challenge_id))? $request->challenge_id : '',
            'status' => 1,
            'created_at' => date('Y-m-d h:i:s')
        );
        
        $saved = TransactionsPoints::create($PostRequest);

        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }

		return $this->services->response(200,"You have been successfully add point",$saved);
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

		if (!$checkUser){
            return $this->getAction->response(406,"User doesnt exist!");
        }

        $profile = $this->getDataServices->employeeLevelDetail($request->byEmployee);
        $activity = $this->getAction->getActivity();

        return $this->getAction->response(200, "Employee Level Details", $profile, $activity);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
