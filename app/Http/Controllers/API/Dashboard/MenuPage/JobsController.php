<?php

namespace App\Http\Controllers\API\Dashboard\MenuPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobsModel;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;

class JobsController extends Controller
{
    public function __construct(){
        $this->getDataServices = new GetDataServices();
        $this->services = new GeneralServices();
        // $this->users = AdminModel::where('is_active', 1);
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
            
            
        $data_jobs = JobsModel::with('company', 'applications', 'job_types')->orderby('job_id', 'desc')->get();

        if ($data_jobs) {
            $action = $this->getAction->getactionrole($checkUser->role_id, 'jobs');
            return $this->getAction->response(200, "Jobs List", $data_jobs, $action);
        } else {
            return $this->services->response(404,"Get Jobs list failed !");
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

		if (!$checkUser){
            return $this->services->response(406,"User doesnt exist!");
        }

        $rules = [
            'company_id' => "required|integer",
            'job_title' => 'required|string',
            'designation_id' => 'required|integer',
            'job_type' => 'required|integer',
            'job_vacancy' => 'required|integer',
            'gender' => 'required|string',
            'minimum_experience' => 'nullable|string',
            'date_of_closing' => 'required|date',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'status' => 'required|integer',
            'country' => 'nullable|string',
            'province' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'districts_id' => 'nullable|integer',
            'subdistrict_id' => 'nullable|integer',
            'currency_id' => 'required|integer',
            'salary_desc' => 'nullable|string',
            'salary_start' => 'integer',
            'salary_end' => 'integer',
        ];
        
        $checkValidate = $this->services->validate($request->all(),$rules);
        
        if(!empty($checkValidate)){
			return $checkValidate;
        }

        $save_jobs = $this->getAction->postJobs($request->all());
        if(!$save_jobs){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Create jobs success",$request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->services->response(406,"User doesnt exist!");
        }

        $data_jobs = $this->getDataServices->getJobs($request->id,$request->user_id);
        if ($data_jobs) {
			// $data_jobs->makeHidden('applications');
			return $this->services->response(200,"Job Show",$data_jobs);
		}else{
			return $this->services->response(404,"Job doesn't exist!");
		}
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
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->services->response(406,"User doesnt exist!");
        }

        $rules = [
            'company_id' => "required|integer",
            'job_title' => 'required|string',
            'designation_id' => 'required|integer',
            'job_type' => 'required|integer',
            'job_vacancy' => 'required|integer',
            'gender' => 'required|string',
            'minimum_experience' => 'nullable|string',
            'date_of_closing' => 'required|date',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'status' => 'required|integer',
            'country' => 'nullable|string',
            'province' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'districts_id' => 'nullable|integer',
            'subdistrict_id' => 'nullable|integer',
            'currency_id' => 'required|integer',
            'salary_desc' => 'nullable|string',
            'salary_start' => 'integer',
            'salary_end' => 'integer',
        ];
        
        $checkValidate = $this->services->validate($request->all(),$rules);
        
        if(!empty($checkValidate)){
			return $checkValidate;
        }

        $update_jobs = $this->getAction->updateJobs($request->all(), $id);
        if(!$update_jobs){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Update jobs success",$request->all());
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

		if (!$checkUser){
            return $this->services->response(406,"User doesnt exist!");
        }

        $delete_jobs = $this->getAction->deleteJobs($request->byJobs);
        if(!$delete_jobs){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Delete jobs success",$request->all());
    }
}
