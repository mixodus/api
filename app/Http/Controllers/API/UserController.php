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
use App\Models\Fase2\UserTrxMailChangeModel;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

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

				if($checkAuth->is_mail_verified == 0){
					return $this->services->response_verify(406,"Pesan verifikasi telah dikirim. Periksa email dan verifikasi akun Anda untuk melanjutkan.");
				}
				$data = $this->services->generateToken($checkAuth);
				$data['user'] = $this->getDataServices->userData($checkAuth->user_id);

				return $this->services->response(200,"Anda berhasil masuk.",$data);
			}else{
				return $this->services->response(406,"Email atau kata sandi salah!");
			}
		}else{
			return $this->services->response(406,"Pengguna tidak ditemukan!");
		}
	}

	public function register(Request $request){
		$rules = [
			'fullname' => "required|string",
			'email' => "required|string|email",
			'contact_no' => "required|string",
			'password' => "required|string|required_with:confirm_password|same:confirm_password",
			'confirm_password' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$getUser = UserModels::where('is_active', 1)->where('email', $request['email'])->first();
		if(!empty($getUser)){
			return $this->services->response(406,"Email ini sudah digunakan!",array());
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
			return $this->services->response(406,"Koneksi jaringan bermasalah!");
		}
		$getPoint = $this->activity_point->where('activity_point_code', 'registration')->first();
		if($getPoint) {
			$save_trx_point = $this->actionServices->postTrxPoints("registration",$getPoint->activity_point_point,$saved->user_id,0,1);
		}
		
		$email = $this->SendMailVerify($saved);
		return $this->services->response(200,"Pendaftaran berhasil. Periksa email dan verifikasi akun Anda untuk melanjutkan!",$saved);
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
		$checkUser = $this->users->where('email', $email)->first();
		if (!$checkUser)
			return $this->services->response(404,"Pengguna tidak ditemukan!");

		// generate reset password on table 
		$code = substr(md5(uniqid(mt_rand(), true)) , 0, 20);
		$save_resetPassword = $this->actionServices->postResetPassword($email,$code);
		
		$data['link'] = 'https://dev-api.oneindonesia.id/site/check-reset?code='.$code.'&email='.$email;
		$sendEmail = $this->services->sendmail('Reset Password | One Talents', $checkUser, 'reset_password', $data);
	
		return $this->services->response(200,"Permintaan Reset Password telah dikirim ke email Anda");
	}

	public function postDeviceID(Request $request){
		$getUser = $this->getDataServices->getUserbyToken($request);
		$rules = ['device_id' => 'required|string'];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $this->services->response(404,"Device ID!");
		}
		$postUpdate = $this->actionServices->postDeviceID($request->all(), $getUser->user_id);
		if (!$postUpdate)
			return $this->services->response(400,"User tidak ditemukan!",null,1);

		return $this->services->response(200,"Device ID berhasil di input!",$request->all());
	}

	public function resetPasswordAction(Request $request){
		$rules = [
			'code' => "required|string",
			'newpassword' => "required|string|required_with:confirm_password|same:confirm_password",
			'confirm_password' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			// return redirect('sites')->with('alert-error','Kesalahan : Konfirmasi kata sandi tidak sama');
			return  redirect()->back()->with('alert-error','Kesalahan : Konfirmasi kata sandi tidak sama');
		}
		
		$checkCode = $this->reset_password->where('code', $request['code'])->first();
		if (!$checkCode)
			return $this->services->response(400,"Kesalahan : Kode verifikasi tidak ditemukan.",null,1);
		$today = date("Y-m-d H:i:s");
		if($today > $checkCode->expired_at) 
			return redirect('sites')->with('alert-error','Kesalahan : Kode verifikasi telah kedaluwarsa.');
			// return $this->services->response(400,"Error : Your verification code have been expired",null,1);
		
		if($checkCode->is_used) 
			// return $this->services->response(400,"Error : Your verification code have been used",null,1);
			return redirect('sites')->with('alert-error','Kesalahan : Kode verifikasi telah digunakan.');
		
		
		$checkUser = $this->users->where('email', $checkCode->email)->first();

		$postUpdate['password'] = $this->services->password_generate($request->newpassword);
		$updatePassword = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate);

		// update reset password data 
		$postUpdateReset['is_used'] = true;
		$updatePassword = $this->reset_password->where('id', $checkCode->id)->update($postUpdateReset);

		// return $this->services->response(200,"You have been successfully to reset password.",null,1);
		
		return redirect('sites')->with('alert-success','Kata sandi berhasil diubah!');
	}
	public function completeProfile(Request $request){
		$rules = [
			'job_title' => "required|string",
			'country' => "required|string",
			'province' => "required|string",
			'expected_salary' => "nullable|integer",
			'summary' => "nullable|string",
			'currency_salary' => "required|string",
			'start_work_year' => "nullable|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		if (!$checkUser) {
			return $this->services->response(406,"Pengguna tidak ditemukan!",array());
		}

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
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Data Profile berhasil di update');

		if(!$updateProfile){
			return $this->services->response(406,"Koneksi jaringan bermasalah!");
		}
		return $this->services->response(200,"Data Profile berhasil di update!", $request->all());
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
			return $this->services->response(406,"Koneksi jaringan bermasalah!");
		}
		return $this->services->response(200,"Keahlian Anda berhasil diperbaharui!", $request->all());
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
				return $this->services->response(406,"Koneksi jaringan bermasalah!");
			}
			return $this->services->response(200,"Kata sandi telah berhasil diubah.", $request->all());
		}else{
			return $this->services->response(406,"Kata sandi tidak sah!");
		}

	}
	public function getProfile(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);

		if (!$checkUser)
			return $this->services->response(406,"Pengguna tidak ditemukan!");

		$profile = $this->getDataServices->userDetail($checkUser->user_id);
		$profile['status_email'] = true;
		if($profile['npwp']==null){
			$profile['npwp'] = "";
		}
		if($profile['skill_text']==null){
			$profile['skill_text'] = "";
		}
		if($checkUser['is_mail_verified']=="0"){
			$profile['status_email'] = false;
		}
		
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
			'email' => "nullable|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$response = $this->services->response(200,"Profil Anda berhasil diperbaharui.", $request->all());
		if(!empty($request['email'])){
			$checkEmail = $this->users->where('user_id', $checkUser->user_id)->where('email', $request->email)->first();
			if (!$checkEmail){
				$checkEmail2 = $this->users->where('email', $request->email)->first();
				if ($checkEmail2){
					return $this->services->response(406,"Email ini telah digunakan!");
				}
				$PostRequest = array(
					'user_id' => $checkUser->user_id,
					'email' => $request['email'],
					'is_mail_verified' => 0,
				);
				$saved = UserTrxMailChangeModel::create($PostRequest);
				$checkUser['email'] = $request['email'];
				$sendVerify = $this->SendMailVerifyChangeEmail($checkUser);
				
				$response = $this->services->response_changemail(200,"Profil Anda berhasil diperbaharui! Email verifikasi berhasil dikirim. Segera verifikasi email baru Anda.", $request->all());
			}
		}
		$postUpdate = $request->except(['r','_method','profile_picture_url','email','profile_picture']);
		// $postUpdate['profile_picture'] = "";
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
		$updateProfile = UserModels::where('user_id', $checkUser->user_id)->update($postUpdate); 

		// if(!$updateProfile){
		// 	return $this->services->response(406,"Koneksi jaringan bermasalah!");
		// }
		return $response;
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
			return $this->services->response(406,"Koneksi jaringan bermasalah!");
		}
		return $this->services->response(200,"Data Profile berhasil diupdate!", array());
			
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
			try {
				$credentials = JWT::decode($request['code'], 'X-Api-Key', array('HS256'));
				
			}catch(SignatureInvalidException $e) {
				return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
			
			} 
			catch(ExpiredException $e) {
				return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
			
			} catch(Exception $e) {
				return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
			
			}
			if($checkVerif->is_mail_verified=='0'){
				$postUpdate['email_verification_code'] = "";
				$postUpdate['is_mail_verified'] = '1';
				$update = UserModels::where('email', $request['email'])->update($postUpdate);
				
				return redirect('sites')->with('alert-success',' Email Anda berhasil di verifikasi.');
			}else{
				return redirect('sites')->with('alert-error','Email Anda telah terverifikasi.');
			}
		}else{
			return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
		}
	}
	public function checkmailVerifyChangeEmail(Request $request){
		$rules = [
			'email' => "required|string",
			'code' => "required|string",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return redirect('sites')->with('alert-error','Invalid Parameters');
		}
		$checkUser = UserTrxMailChangeModel::where('email',$request['email'])->first();
		
		if(!empty($checkUser)){
			$checkVerif = UserModels::where('user_id',$checkUser->user_id)->where('email_verification_code',$request['code'])->first();
			if(!empty($checkVerif)){
				try {
					$credentials = JWT::decode($request['code'], 'X-Api-Key', array('HS256'));
					
				}catch(SignatureInvalidException $e) {
					return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
				} 
				catch(ExpiredException $e) {
					return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
				} catch(Exception $e) {
					return redirect('sites')->with('alert-error','Link verifikasi telah kedaluwarsa. Mohon masuk kembali untuk mendapatkan email verifikasi baru.');
				}
				if($checkUser->is_mail_verified==null || empty($checkUser->is_mail_verified)){
					$postUpdate['email_verification_code'] = "";
					$postUpdate['is_mail_verified'] = '1';
					$postUpdate['email'] = $request['email'];
					$update = UserModels::where('user_id', $checkUser->user_id)->update($postUpdate);

					UserTrxMailChangeModel::where('user_id', $checkUser->user_id)->delete();
					
					return redirect('sites')->with('alert-success','Email berhasil di verifikasi dan terupdate!');
				}else{
					return redirect('sites')->with('alert-error','Email-mu telah terverifikasi');
				}
			}else{
				return redirect('sites')->with('alert-error','Pengguna tidak ditemukan atau kode expired');
			}
		}else{
			return redirect('sites')->with('alert-error','Invalid User');
		}
	}
	public function VerifyMail(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		
		if (!$checkUser)
			return $this->services->response(404,"Pengguna tidak ditemukan!");
		// send mail
		return $this->SendMailVerify($checkUser);
	}
	public function RequestVerifyMail(Request $request){
		$checkUser = $this->users->where('email', $request->email)->first();
		
		if (!$checkUser)
			return $this->services->response(404,"Pengguna tidak ditemukan!");
		// send mail
		return $this->SendMailVerify($checkUser);
	}

	public function SendMailVerify($userData){
		$code = $this->services->generateTokenVerify($userData->user_id);
		$postUpdate['email_verification_code']  = $code;
		$updateCodeVerif = $this->users->where('user_id', $userData->user_id)->update($postUpdate);
		
		$data['link'] = 'https://dev-api.oneindonesia.id/site/user/check-verify?code='.$code.'&email='.$userData->email;
		$sendEmail = $this->services->sendmail('Verifikasi Email | One Talent', $userData, 'verify_email', $data);
	
		return $this->services->response(200,"Email Verifikasi telah dikirim ke email anda ".$userData->email,$sendEmail);
	}
	public function SendMailVerifyChangeEmail($userData){
		$code = $this->services->generateTokenVerify($userData->user_id);
		$postUpdate['email_verification_code']  = $code;
		$updateCodeVerif = UserModels::where('user_id', $userData->user_id)->update($postUpdate);
		
		
		$data['link'] = 'https://dev-api.oneindonesia.id/site/user/check-verify-change-email?code='.$code.'&email='.$userData->email;
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
			return $this->services->response(406,"Koneksi jaringan bermasalah!");
		}
		return $this->services->response(200,"NPWP telah ditambahkan!", $request->all());
	}
	public function DeviceToken(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		if(empty($checkUser)){
			return $this->services->response(406,"Anda harus login untuk mengakases API ini!");
		}
		$rules = [
			'device_token' => "required|string"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$postUpdate['device_token'] = $request->device_token;
		$updateProfile = $this->users->where('user_id', $checkUser->user_id)->update($postUpdate); 

		if(!$updateProfile){
			return $this->services->response(406,"Koneksi jaringan bermasalah!");
		}
		return $this->services->response(200,"Device Token telah ditambahkan!", $request->all());
	}
}
