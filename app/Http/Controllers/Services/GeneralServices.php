<?php

namespace App\Http\Controllers\Services;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use DateTime;
use Mail;

class GeneralServices extends BaseController
{
	public function __construct(){
	}

	public function validate($request, $rules){
		$validator = Validator::make($request,$rules);
		if ($validator->fails()) {
			$response = [
				'status' => false,
				'message' =>  $validator->errors()->first()
			];
			return response()->json($response, 406);
		}
	}
	public function generateToken($dataUser,$key = false)
	{
		if (!$key) {
			$key = 'X-Api-Key';
		}
		//comment coding lama
		// $date = new DateTime();
		// $token['iat'] = $date->getTimestamp(); // waktu dibuat
		// $token['exp'] = $date->getTimestamp() + (3600*0.1) ; // 1 hour

		$exp = 7776000;
		$date = new DateTime();
		$token['data'] = ['id' => $dataUser->user_id];
		$token['iat'] = $date->getTimestamp();
		$token['exp'] = $date->getTimestamp() + 86400*7; // a week 
		
		$data['token'] = JWT::encode($token, $key);
		$data['expiration'] =  ["second"=>$exp,'hours' => $exp / (60 * 60 * 7)];
		return $data;

	}

	public function generateTokenVerify($dataUser,$key = false)
	{
		if (!$key) {
			$key = 'X-Api-Key';
		}
		//comment coding lama
		// $date = new DateTime();
		// $token['iat'] = $date->getTimestamp(); // waktu dibuat
		// $token['exp'] = $date->getTimestamp() + (3600*0.1) ; // 1 hour

		$exp = 7776000;
		$date = new DateTime();
		$token['data'] = ['id_user' => $dataUser];
		$token['iat'] = $date->getTimestamp();
		$token['exp'] = $date->getTimestamp() + 86400*2; // a week 
		
		return JWT::encode($token, $key);

	}
	public function response($statusCode, $msg, $data=array(),$with_alert= null){
		$response = [
			'status' => true,
			'message' => $msg,
			'data' => $data,
		];
		if ($statusCode != 200 && $data == array()) {
			$response = [
				'status' => false,
				'message' => $msg,
				'data' => $data
			];
		}
		if ($with_alert != null && $statusCode != 200) {
			$response = [
				'status' 	=> false,
				'alert' 	=> 'failed',
				'message' 	=> $msg
			];
			return response()->json($response, 200);
		}
		if ($with_alert != null && $statusCode != 200) {
			$response = [
				'status' 	=> true,
				'alert' 	=> 'success',
				'message' 	=> $msg
			];
		}
		return response()->json($response, $statusCode);
	}
	public function response_verify($statusCode, $msg, $data=array(),$with_alert= null){
		$response = [
			'status' => true,
			'isVerified' => true,
			'message' => $msg,
			'data' => $data,
		];
		if ($statusCode != 200) {
			$response = [
				'status' => false,
				'isVerified' => false,
				'message' => $msg
			];
		}
		return response()->json($response, $statusCode);
	}
	public function response_changemail($statusCode, $msg, $data=array(),$with_alert= null){
		$response = [
			'status' => true,
			'isEmailChanged' => true,
			'message' => $msg,
			'data' => $data,
		];
		if ($statusCode != 200) {
			$response = [
				'status' => false,
				'isEmailChanged' => false,
				'message' => $msg
			];
		}
		return response()->json($response, $statusCode);
	}

	public function clean_post($post_name) {
		$name = trim($post_name);
		$Evalue = array('-','alert','<script>','</script>','</php>','<php>','<p>','\r\n','\n','\r','=',"'",'/','cmd','!',"('","')", '|');
		$post_name = str_replace($Evalue, '', $name); 
		$post_name = preg_replace('/^(\d{1,2}[^0-9])/m', '', $post_name);
		return $post_name;
	}
	
	public function password_generate($password){
		return password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
	}
	public function randomid($digits){
		return rand(pow(10, $digits-1), pow(10, $digits)-1);
	}

	public function sendmail($subject, $user, $layout,$data){
		$toname = $user['fullname'];
		$tomail = $user['email'];
		$frommail = env('MAIL_FROM_ADDRESS','regita.lisgiani@idstar.co.id');
		$fromname = env('MAIL_FROM_NAME','One Apps Talents'); 
		$success = array();
		$failures = array();
		//TRY CATCH SENT EMAIL
		try{
			Mail::send("mail.".$layout, $data, function($message) use ($toname, $tomail, $fromname, $frommail, $subject){
				$message->to($tomail, $toname)->subject($subject);
				$message->from($frommail, $fromname);
			});
			$success[] = $tomail;
		}catch (Exception $e) {
			if (count(Mail::failures()) > 0) {
				$failures[] = $tomail;
			}
		}
		$statusMail['successSent'] = $success;
		$statusMail['failureSent'] = $failures;

		return $statusMail;
	}
}
