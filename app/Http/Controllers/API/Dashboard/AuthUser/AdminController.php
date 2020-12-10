<?php

namespace App\Http\Controllers\API\dashboard\AuthUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\Dashboard\AdminModel;
 

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
            return $this->services->response(200,"Admin List",$getAdmin);
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
            'last_name' => "required|string",
			'email' => "required|string|email|unique:xin_users,email",
			'contact_no' => "required|string",
			'password' => "required|string|required_with:confirm_password|same:confirm_password",
			'confirm_password' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
        $PostRequest = array(
			'user_id' => $this->services->randomid(4),
            'first_name' => $this->services->clean_post($request['first_name']),
            'last_name' => $this->services->clean_post($request['last_name']),
            'username' => $this->services->clean_post($request['email']),
            'email' => $this->services->clean_post($request['email']),
            'password' => $this->services->password_generate($request->confirm_password),
            'contact_no' => $this->services->clean_post($request['contact_no']),
            'role_id' => $request['role_id'],
            'is_active' => 1,
            'created_at' => date('Y-m-d h:i:s')
        );
        
		$saved = AdminModel::create($PostRequest);

        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }
		// $getPoint = $this->activity_point->where('activity_point_code', 'registration')->first();
		// if($getPoint) {
		// 	$save_trx_point = $this->actionServices->postTrxPoints("registration",$getPoint->activity_point_point,$saved->user_id,0,1);
		// }
		return $this->services->response(200,"You have been successfully registered",$saved);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
