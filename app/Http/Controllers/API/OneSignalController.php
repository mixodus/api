<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ladumor\OneSignal\OneSignal;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;

class OneSignalController extends Controller
{
    public function __construct(){
        $this->services = new GeneralServices();
        $this->actionServices = new ActionServices();
        $this->getDataServices = new GetDataServices();
    }
    public function pushNotification(Request $request){
        $checkUser = $this->getDataServices->getUserbyToken($request);
        if(empty($checkUser)){
            return $this->services->response(406, "User Not Found!");
        }
        $data = $this->services->oneSignalNotification($checkUser, "Services Test!");
        return $this->services->response(200, "Onesignal Push Message", $data);
    }
    public function pushNotificationManual(Request $request){
        $fields['include_player_ids'] = ['e59321d1-36d7-4c83-a67c-314c02d03ffd'];
        $text = "TEST DEVELOPMENT!";
		$data = OneSignal::sendPush($fields, $text);
		$data['message'] = [$text];
		return $this->services->response(200, "Onesignal Push Message", $data);
    }
    public function getNotification(){
      return OneSignal::getNotifications();
    }
}
