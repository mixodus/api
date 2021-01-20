<?php

namespace App\Http\Controllers\API\dashboard\AuthUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\Dashboard\AdminModel;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Models\RolesModel;

class AdminController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
        $this->getDataServices = new GetDataServices();
        $this->admin = AdminModel::where('is_active', 1);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        if ($checkUser->role_id != 1) {
            return $this->show_error("Unauthorized User");
        }

        $getAdmin   = AdminModel::paginate();
        if (!$getAdmin->isEmpty()) {
            $action = $this->actionServices->getactionrole($checkUser->role_id, 'admin');
            return $this->actionServices->response(200, "Admin List", $getAdmin, $action);
		}else{
			return $this->services->response(200,"Event Type Doesnt Exists!");
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $rules = [
			'username' => "required|string",
			'password' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

        $checkAuth = $this->admin->where('email', $request['username'])->first();
        
		if ($checkAuth) {
			$password_hash = password_hash($request['password'], PASSWORD_BCRYPT, array('cost' => 12));
			if(password_verify($request['password'],$checkAuth->password)){

				$data = $this->services->generateToken($checkAuth);
				$data['user'] = $this->getDataServices->userData($checkAuth->user_id);

				return $this->services->response(200,"login success",$data);
			}else{
				return $this->services->response(401,"Username and Password doesn't Match. Please Try Again !");
			}
		}else{
			return $this->services->response(404,"User doesnt exist!");
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $rules = [
            'first_name' => "required|string",
            'company_name' => 'required',
            'role_id' => 'required',
			'email' => "required|string|email|unique:xin_users,email",
			'password' => ['required', 'min:6', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/','required_with:confrim_password','same:confrim_password'],
			'confrim_password' => "required",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
        
        $check_role = RolesModel::where('role_id', $request->role_id)->first();
        
        $PostRequest = array(
            'user_id' => $this->services->randomid(4),
            'user_role' => $check_role->role_name,
            'company_name' => $this->services->clean_post($request['company_name']),
            'first_name' => $this->services->clean_post($request['first_name']),
            'username' => $this->services->clean_post($request['email']),
            'email' => $this->services->clean_post($request['email']),
            'password' => $this->services->password_generate($request->confrim_password),
            'role_id' => $request['role_id'],
            'is_active' => 1,
        );
        
		$saved = AdminModel::create($PostRequest);

        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }

		return $this->services->response(200,"You have been successfully registered",$saved);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = AdminModel::where("user_id", $request->user_id)->get();

        if ($data) {
            return $this->services->response(200, "Admin show", $data);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Failed to load data"
            ]);
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
        if ($checkUser->role_id != 1) {
            return $this->show_error("Unauthorized User");
        }

        $rules = [
            'first_name' => "required|string|max:10|min:3",
            'company_name' => 'required',
            'role_id' => 'required',
            'is_active' => 'required|integer|max:1'
        ];
        
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }
        $check_role = RolesModel::where('role_id', $request->role_id)->first();
        $update = AdminModel::where('user_id', $id)->update([
                            'first_name' => $this->services->clean_post($request['first_name']),
                            'role_id' => $request['role_id'],
                            'user_role' => $check_role->role_name,
                            'company_name' => $this->services->clean_post($request['company_name']),
                            'is_active' => $this->services->clean_post($request['is_active'])
                        ]);

        if(!$update){
			return $this->services->response(503,"Server Error!");
        }

        return $this->services->response(200,"You have been successfully update",$update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        if ($checkUser->role_id != 1) {
            return $this->show_error("Unauthorized User");
        }

        $delete = AdminModel::where('user_id', $id)->delete();
        if(!$delete){
			return $this->services->response(503,"Server Error!");
        }

        return $this->services->response(200,"You have been successfully delete",$delete);
    }
}
