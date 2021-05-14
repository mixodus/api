<?php

namespace App\Helpers\Helpers;

use Illuminate\Http\Request;
use Validator;
use Symfony\Component\HttpFoundation\Response;

// class Helpers{

	function ValidateRequest($params,$rules){

		$validator = validate::make($params, $rules);

		if ($validator->fails()) {
			$response = [
				'status' => false,
				'message' => $validator->messages()
			];
			return response()->json($response, Response::HTTP_NOT_ACCEPTABLE);
		}
	}
// }

