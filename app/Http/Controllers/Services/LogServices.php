<?php

namespace App\Http\Controllers\Services;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Fase2\LogModel;

class LogServices extends BaseController
{
	public function log($data){
		$postData['server_type'] = env('SERVER_TYPE');
		$postData['type'] = $data['type'];
		$postData['name'] = $data['name'];
		$postData['user_id'] = $data['user_id'];
		$postData['version'] = env('APP_VERSION');
		$postData['ip_address'] = $data['ip_address'];
		$postData['method'] = $data['method'];
		$postData['request_header'] = $data['request_header'];
		$postData['request_body'] = $data['request_body'];
		$postData['response'] = $data['response'];
		$postData['status_code'] = $data['status_code'];
		
		LogModel::create($postData);
	}

}
