<?php

namespace App\Http\Controllers\API\Dashboard\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\Dashboard\AdminModel;
use App\Models\UserModels;

class EmployeeController extends Controller
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
        
        $data_employee = UserModels::get();
        
        if ($data_employee) {
            $action = $this->getAction->getactionrole($checkUser->role_id, 'employee');
            return $this->getAction->response(200, "Employee List", $data_employee, $action);
        } else {
            return $this->services->response(404,"Get employee list failed !");
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
        //
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
			
		$profile = $this->getDataServices->userDetail($request->byEmployee);
		
		return response()->json($profile, 200);
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
        if (!$checkUser) {
            return $this->getAction->response(406,"User doesnt exist!");
        }

		$rules = [
			'fullname' => "required|string",
			'contact_no' => "required|string",
			'country' => "required|string",
			'province' => "required|string",
			'date_of_birth' => "required|string",
			'marital_status' => "required|string",
			'gender' => "required|string|in:male,female,Male,Female",
			'job_title' => "nullable|string",
			'zip_code' => "nullable|string",
			'summary' => "nullable|string",
			'address' => "nullable|string",
			'profile_picture' => "nullable|string",
            'npwp' => "nullable|string",
            'is_active' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
        // $postUpdate = $request->all();
		$imgname = null;
		if($request->profile_picture != null){
			$image = $request->file('photo');
			$imgname = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = public_path('/uploads/profile/');
			$image->move($destinationPath, $imgname);
			
			// $postUpdate = $imgname;
		}
        // dd($imgname);
        $updateEmployee = UserModels::where('user_id', $id)->update([
            'fullname' => $request->fullname,
			'contact_no' => $request->contact_no,
			'country' => $request->country,
			'province' => $request->province,
			'date_of_birth' => $request->date_of_birth,
			'marital_status' => $request->marital_status,
			'gender' => $request->gender,
			'job_title' => $request->job_title,
			'zip_code' => $request->zip_code,
			'summary' => $request->summary,
			'address' => $request->address,
			'profile_picture' => $imgname,
            'npwp' => $request->npwp,
            'is_active' => $request->is_active
        ]);

		// $updateEmployee = UserModels::where('user_id', $id)->update($postUpdate); 

		if(!$updateEmployee){
			return $this->services->response(406,"Server Error!");
		}
		return $this->services->response(200,"Your employee has been updated!", $request->all());
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
            return $this->getAction->response(406,"User doesnt exist!");
        }

        $delete = UserModels::where('user_id', $request->byEmployee)->delete();
        if(!$delete){
			return $this->getAction->response(503,"Server Error!");
        }

        return $this->getAction->response(200,"You have been successfully delete",$delete);
    }
}
