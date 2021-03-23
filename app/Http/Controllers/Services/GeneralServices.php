<?php

namespace App\Http\Controllers\Services;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use DateTime;
use Mail;
use GuzzleHttp\Client;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Models\UserModels;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\Services\ActionServices;

class GeneralServices extends BaseController
{
	public function __construct(){
        $this->getDataServices = new GetDataServices();
		$this->actionServices = new ActionServices();
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
	public function validate2($request, $rules){
		$validator = Validator::make($request,$rules);
		if ($validator->fails()) {
			return $validator->errors()->first();
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
		$token['exp'] = $date->getTimestamp() + 86400*365; // a years 
		
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
				$message->from('regitalisgianidrajat@gmail.com', "One Talent App");
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
	public static function sendNotif($user_id,$message){
		$deviceToken = UserModels::select('device_token')->where('user_id',$user_id)->first();
        if(!empty($deviceToken['device_token'])){
			
            $data = [
				'to'=> $deviceToken['device_token'],
				'notification'=>[
					'title' => 'One Apps | Hackathon',
					'body'  =>$message,
					'mutable_content' => true,
				],
				'data'=> [
					'notification_type' => 1,
					'event_id' => 26,
					'message'=> $message,
				],
				'priority'=>10
			];
			$client = new Client();
			$firebase_key = env('SERVER_KEY');
       		$url = 'https://fcm.googleapis.com/fcm/send';
			   $headers = [
				'Authorization' => 'key='.$firebase_key,
				'Content-Type'  => 'application/json'
			];

			$timeout = ['connection_timeout' => 600,'timeout'=> 600];
			try {
				$response = $client->POST($url,[
					'headers' => $headers,
					'body' => json_encode($data),
					$timeout
				]);
			}
			catch (ClientException $e) {
				return response()->json([
					'status' =>false,
					'message' => 'Invalid Device Token ('.$e->getMessage().')',
				], 406);
		
		   }
		   	$save_notif = $this->actionServices->postNotif(5,0,$user_id,$message);
			return json_decode($response->getBody(),true);
	
        }else{
			return "Device Token Not Found";
		}
    }

}
