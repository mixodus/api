<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\NewsModel;

class NewsController extends Controller
{
	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->newsModel = NewsModel::select('*');
	}
	
	public function index(Request $request){

		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$getNews = $this->getDataServices->getNews();	

		if (!$getNews->isEmpty()) {
			return $this->services->response(200,"List News",$getNews);
		}else{
			return $this->services->response(200,"News doesnt exist!");
		}
	}
	public function detail(Request $request){

		$getNews = $this->getDataServices->getNews($request->id);	

		if (!$getNews->isEmpty()) {
			return $this->services->response(200,"List News",$getNews);
		}else{
			return $this->services->response(200,"News doesnt exist!");
		}
	}
}
