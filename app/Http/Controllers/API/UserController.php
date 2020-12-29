<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\UserModels;
use App\Models\ActivitiesPointModel;
use App\Models\TransactionsPoints;
use App\Models\PointModels;
use App\Models\ResetPasswordModel;

class UserController extends BaseController
{
	private $users;
	private $activity_point;

	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->users = UserModels::where('is_active', 1);
		$this->activity_point = ActivitiesPointModel::select('*');
		$this->reset_password = ResetPasswordModel::select('*');
	}

	public function login(Request $request){

		$rules = [
			'username' => "required|string",
			'password' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

		$checkAuth = $this->users->where('email', $request['username'])->first();
		if ($checkAuth) {
			$password_hash = password_hash($request['password'], PASSWORD_BCRYPT, array('cost' => 12));
			if(password_verify($request['password'],$checkAuth->password)){

				$data = $this->services->generateToken($checkAuth);
				$data['user'] = $this->getDataServices->userData($checkAuth->user_id);

				return $this->services->response(200,"login success",$data);
			}else{
				return $this->services->response(406,"Username and Password doesn't Match. Please Try Again !");
			}
		}else{
			return $this->services->response(406,"User doesnt exist!");
		}
	}

	public function register(Request $request){
		$rules = [
			'fullname' => "required|string",
			'email' => "required|string|email|unique:xin_employees,email",
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
			'fullname' => $this->services->clean_post($request['fullname']),
			'username' => $this->services->clean_post($request['email']),
			'email' => $this->services->clean_post($request['email']),
			'password' => $this->services->password_generate($request->confirm_password),
			'contact_no' => $this->services->clean_post($request['contact_no']),
			'first_name' => '',
			'last_name' => '',
			'is_active' => 1,
			'created_at' => date('Y-m-d h:i:s')
		);
		$saved = UserModels::create($PostRequest);

		if(!$saved){
			return $this->services->response(406,"Server Error!");
		}
		$getPoint = $this->activity_point->where('activity_point_code', 'registration')->first();
		if($getPoint) {
			$save_trx_point = $this->actionServices->postTrxPoints("registration",$getPoint->activity_point_point,$saved->user_id,0,1);
		}
		
		$email = $this->SendMailVerify($saved);
		return $this->services->response(200,"You have been successfully registered, Please check your email to verify your email",$saved);
	}
	public function resetPassword(Request $request){
		$rules = [
			'email' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

		$email = $this->services->clean_post($request['email']);
		
		// cek email available or not on xin_employee 
		$checkUser = $this->users->where('username', $email)->first();
		if (!$checkUser)
			return $this->services->response(404,"User doesnt exist!");

		// generate reset password on table 
		$code = substr(md5(uniqid(mt_rand(), true)) , 0, 20);
		$save_resetPassword = $this->actionServices->postResetPassword($email,$code);
		
		$data['link'] = env('URL_RESET').'?code='.$code.'&email='.$email;
		$sendEmail = $this->services->sendmail('Reset Password | One Talents', $checkUser, 'reset_password', $data);
	
		return $this->services->response(200,"A confirmation email has been send to your email address ".$email,$sendEmail);
	}

	public function resetPasswordAction(Request $request){
		$rules = [
			'code' => "required|string",
			'newpassword' => "required|string|required_with:confirm_password|same:confirm_password",
			'confirm_password' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		
		$checkCode = $this->reset_password->where('code', $request['code'])->first();
		if (!$checkCode)
			return $this->services->response(400,"Error : Wrong verification code",null,1);
		$today = date("Y-m-d H:i:s");
		if($today > $checkCode->expired_at) 
			return redirect('sites')->with('alert-error','Error : Your verification code have been expired');
			// return $this->services->response(400,"Error : Your verification code have been expired",null,1);
		
		if($checkCode->is_used) 
			// return $this->services->response(400,"Error : Your verification code have been used",null,1);
			return redirect('sites')->with('alert-error','Error : Your verification code have been used');
		
		
		$checkUser = $this->users->where('email', $checkCode->email)->first();

		$postUpdate['password'] = $this->services->password_generate($request->newpassword);
		$updatePassword = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate);

		// update reset password data 
		$postUpdateReset['is_used'] = true;
		$updatePassword = $this->reset_password->where('id', $checkCode->id)->update($postUpdateReset);

		// return $this->services->response(200,"You have been successfully to reset password.",null,1);
		
		return redirect('sites')->with('alert-success','Password berhasil diubah!');

	}
	
	public function completeProfile(Request $request){
		$rules = [
			'job_title' => "required|string",
			'country' => "required|string",
			'province' => "required|string",
			'expected_salary' => "nullable|integer",
			'summary' => "required|string",
			'currency_salary' => "required|string",
			'start_work_year' => "nullable|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);

		$postUpdate = array(
			'country' => $request['country'],
			'job_title' =>$request['job_title'],
			'province' => $request['province'],
			'expected_salary' => $request['expected_salary'],
			'summary' => $request['summary'],
			'currency_salary' => $request['currency_salary'],
			'start_work_year' => $request['start_work_year']
		); 
		
		$updateProfile = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate); 
		//notif
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Your profile has been updated');

		if(!$updateProfile){
			return $this->services->response(406,"Server Error!");
		}
		return $this->services->response(200,"Your profile has been updated!", $request->all());
	}
	public function updateSkill(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'skill' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

		$checkUser = $this->getDataServices->getUserbyToken($request);

		$postUpdate['skill_text']= $request->skill;
		$updateSkill = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate); 

		//notif
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Your skill has been updated');

		//point
		$getPoint = $this->activity_point->where('activity_point_code', 'add_skill')->first();

		$save_trx_point = $this->actionServices->postTrxPoints("add_skill",$getPoint->activity_point_point,$checkUser->user_id,0,1);
		if(!$updateSkill){
			return $this->services->response(406,"Server Error!");
		}
		return $this->services->response(200,"Your skills has been updated!", $request->all());
	}
	public function changePassword(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'password' => "required|string",
			'newpassword' => "required|string|required_with:confirm_newpassword|same:confirm_newpassword|min:6",
			'confirm_newpassword' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkAuth = $this->users->where('user_id',$checkUser->user_id)->first();

		$password_hash = password_hash($request['password'], PASSWORD_BCRYPT, array('cost' => 12));
		if(password_verify($request['password'],$checkAuth->password)){

			$postUpdate['password'] = $this->services->password_generate($request->newpassword);
			$updatePassword = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate);
			if(!$updatePassword){
				return $this->services->response(406,"Server Error!");
			}
			return $this->services->response(200,"You have been successfully change password.", $request->all());
		}else{
			return $this->services->response(406,"Invalid Current Password!");
		}

	}
	public function getProfile(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);

		if (!$checkUser)
			return $this->services->response(406,"User doesnt exist!");

		$profile = $this->getDataServices->userDetail($checkUser->user_id);
		
		return response()->json($profile, 200);
	}

	public function friendProfile($id){
		$profile = $this->getDataServices->userDetail($id);
		
		return response()->json($profile, 200);
	}

	public function updateProfile(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
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
			'profile_picture' => "nullable",
			'npwp' => "nullable|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$postUpdate = $request->except(['r','_method','profile_picture_url']);
		$postUpdate['profile_picture'] = "";
		if($request->profile_picture != null && $request->profile_picture != null){
			// $image = $request->file('profile_picture');
			// $imgname = time().'.'.$image->getClientOriginalExtension();
			// $img = str_replace('data:image/jpeg;base64,', '', $request['profile_picture']);
            // $img = str_replace(' ', '+', $img);
            // $data_file = base64_decode($img);
			// $destinationPath = public_path('/uploads/profile/');
            // $imgname = "profile_".round(microtime(true)).".jpeg";
			// $img->move($destinationPath, $data_file);
			$extension = explode('/', explode(':', substr($request->profile_picture, 0, strpos($request->profile_picture, ';')))[1])[1];
			// $image = $request->profile_picture;  // your base64 encoded
			// $image = str_replace('data:image/png;base64,', '', $image);
			// $image = str_replace(' ', '+', $image);
			// $imageName = str_random(10).'.'.$extension;
			$folder = 'uploads/profile/';
			if($extension =="jpeg"){
				$img = str_replace('data:image/jpeg;base64,', '', $request['profile_picture']);
			}elseif($extension == "png"){
				$img = str_replace('data:image/png;base64,', '', $request['profile_picture']);
			}elseif($extension =="jpg"){
				$img = str_replace('data:image/jpg;base64,', '', $request['profile_picture']);
			}else{
				return $this->services->response(406,"Format gambar tidak mendukung!");
			}
            $img = str_replace(' ', '+', $img);
            $data_file = base64_decode($img);
            $filename = "profile_".round(microtime(true)).'.'.$extension;
            $path = $folder . $filename;
			file_put_contents($path, $data_file);
			
			$postUpdate['profile_picture'] = $filename;
		}
		// return $postUpdate;
		$updateProfile = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate); 

		if(!$updateProfile){
			return $this->services->response(406,"Server Error!");
		}
		return $this->services->response(200,"Your profile has been updated!", $request->all());
	}
	
	public function uploadPicture(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'photo' => "required"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}

		$image = $request->file('photo');
		$imgname = time().'.'.$image->getClientOriginalExtension();
		$destinationPath = public_path('/uploads/profile/');
		$image->move($destinationPath, $imgname);
		
		$postUpdate['profile_picture'] = $imgname;

		$updateProfile = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate); 
		
		//notif
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Your photo successfully uploaded');

		if(!$updateProfile){
			return $this->services->response(406,"Server Error!");
		}
		return $this->services->response(200,"Your profile has been updated!", array());
			
	}
	public function checkmailVerified(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);

		if ($checkUser)
			$data['is_mail_verified'] = $checkUser['is_mail_verified'];
			$data['status'] = boolval($checkUser['is_mail_verified']);

		return $this->services->response(200,"Status Email", $data);
	}
	public function checkmailVerify(Request $request){
		$rules = [
			'email' => "required|string",
			'code' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return redirect('sites')->with('alert-error','Invalid Parameters');
		}
		$checkVerif = $this->users->where('email',$request['email'])->where('email_verification_code',$request['code'])->first();
		if(!empty($checkVerif)){
			$postUpdate['email_verification_code'] = "";
			$postUpdate['is_mail_verified'] = 1;
			$update = UserModels::where('user_id', $checkVerif->user_id)->update($postUpdate);
			
			return redirect('sites')->with('alert-success','Email berhasil di verifikasi!');
		}else{
			return redirect('sites')->with('alert-error','Emailmu telah terverifikasi atau Url Verification-mu telah expired mohon kirim ulang verification email kembali');
		}
	}
	public function VerifyMail(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		
		if (!$checkUser)
			return $this->services->response(404,"User doesnt exist!");
		// send mail
		return $this->SendMailVerify($checkUser);
	}
	public function SendMailVerify($userData){
		$code = $userData->user_id.'000'.substr(md5(uniqid(mt_rand(), true)) , 0, 15);
		$postUpdate['email_verification_code']  = $code;
		$updateCodeVerif = $this->users->where('user_id', $userData->user_id)->update($postUpdate);
		
		$data['link'] = env('URL_VERIFY').'?code='.$code.'&email='.$userData->email;
		$sendEmail = $this->services->sendmail('Verifikasi Email | One Talent', $userData, 'verify_email', $data);
	
		return $this->services->response(200,"Email Verifikasi telah dikirim ke email anda ".$userData->email,$sendEmail);
	}
	public function checkResetPassword(Request $request){
		$rules = [
			'email' => "required|string",
			'code' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return redirect('sites')->with('alert-error','Invalid Parameters');
		}$checkVerif = $this->reset_password->where('code', $request['code'])->first();
		if(!empty($checkVerif)){
			$data['email'] = $request->email;
			$data['code'] = $request->code;
			$data['title'] = "One Talent";
			return view('general.site_reset_password', $data);
		}else{
			return redirect('sites')->with('alert-error','Emailmu telah terverifikasi atau Url Verification-mu telah expired mohon kirim ulang verification email kembali');
		}
		
	}
	public function checkNpwp(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);

		if ($checkUser)
			$data['npwp'] =$checkUser['npwp'];
			$data['status_npwp'] = false;
			if($checkUser['npwp'] != null && $checkUser['npwp'] != "")
				$data['status_npwp'] = true;

		return $this->services->response(200,"Status Email", $data);
	}
	
	public function updateNpwp(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'npwp' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$postUpdate = $request->all();
		$updateProfile = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate); 

		if(!$updateProfile){
			return $this->services->response(406,"Server Error!");
		}
		return $this->services->response(200,"NPWP telah ditambahkan!", $request->all());
	}
}
