<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Validator;
use Symfony\Component\HttpFoundation\Response;

class AppResponses
{
	public function __construct() {
	}
	
	public function ResponseJson($status,$message,$data = null,$http_reponse){
		$response = [
			'status' => true,
			'message' => $message,
			'data' => $data
		];
		if($status == "false"){
			$response = ['status' => false];
		}
		return response()->json($response, $http_reponse);
	}

}