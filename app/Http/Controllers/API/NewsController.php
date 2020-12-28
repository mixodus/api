<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\NewsModel;
use App\Models\ActivitiesPointModel;

class NewsController extends Controller
{
	private $activity_point;

	public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->newsModel = NewsModel::select('*');
		$this->activity_point = ActivitiesPointModel::select('*');
	}
	
	public function index(Request $request){

		$rules = [
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		
		if(!empty($request['start'])){
			$request['start'] = $request['start'];
		}else{
			$request['start'] = 0;
		}
		if(!empty($request['length'])){
			$request['length'] = $request['length'];
		}else{
			$request['length'] = 25;
		}
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$getNews = $this->getDataServices->getNews();	

		if (!$getNews->isEmpty()) {
			return $this->services->response(200,"List News",$getNews);
		}else{
			return $this->services->response(200,"News doesnt exist!",array());
		}
	}
	public function detail(Request $request){
		$request['id'] = $request['id'];
		if(!empty($request['start'])){
			$request['start'] = $request['start'];
		}else{
			$request['start'] = 0;
		}
		if(!empty($request['length'])){
			$request['length'] = $request['length'];
		}else{
			$request['length'] = 25;
		}
		$getNews = $this->getDataServices->getNews($request->all());
		if (!$getNews->isEmpty()) {
			return $this->services->response(200,"List News",$getNews);
		}else{
			return $this->services->response(200,"News doesnt exist!",array());
		}
	}

	//=========================FASE 2=======================================//
	public function getComment(Request $request){
		$rules = [
			'news_id' => "required|integer",
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$data = $this->getDataServices->getNewsComment($request->all());	

		if (!$data->isEmpty()) {
			return $this->services->response(200,"Komentar Berita atau Artikel",$data);
		}else{
			return $this->services->response(200,"Komentar tidak ditemukan!",array());
		}
	}
	public function getCommentDetail(Request $request){
		$rules = [
			'comment_id' => "required|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$data = $this->getDataServices->getNewsCommentDetail($request->comment_id);	
		if ($data) {
			return $this->services->response(200,"Komentar Berita atau Artikel",$data);
		}else{
			return $this->services->response(200,"Komentar tidak ditemukan!",array());
		}
	}
	public function getReplyComment(Request $request){
		$rules = [
			'comment_id' => "required|integer",
			'start' => "nullable|integer",
			'length' => "nullable|integer"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate))
			return $checkValidate;
		
		$data = $this->getDataServices->getNewsReplyComment($request->all());	

		if (!$data->isEmpty()) {
			return $this->services->response(200,"Balasan Komentar",$data);
		}else{
			return $this->services->response(200,"Balasan komentar tidak ditemukan!",array());
		}
	}
	public function addComment(Request $request){
		$rules = [
			'type' => "required|in:comment,reply_comment",
			'news_id' => "required_if:type,==,comment",
			'comment_id' => "required_if:type,==,reply_comment",
			'comment' => "required|string",
			'attachment' => "nullable|string",
			'desc' => "nullable|string"
		];
		$checkUser = $this->getDataServices->getUserbyToken($request);
		if($request->type =="comment"){
			$save = $this->actionServices->postComment($request->all(),$checkUser->user_id);
		}else{
			$data = $this->getDataServices->getNewsCommentDetail($request->comment_id);
			$request['user_id'] = $data['user_id'];
			$save = $this->actionServices->postReplyComment($request->all(),$checkUser->user_id);
		}
		if(!$save)
			return $this->services->response(503,"Server Error!");
		 
		$getPoint = $this->activity_point->where('activity_point_code', 'add_news_comment')->first();
		if($getPoint) {
			$save_trx_point = $this->actionServices->postTrxPoints("add_news_comment",$getPoint->activity_point_point,$checkUser->user_id,0,1);
		}
		return $this->services->response(200,"Komentar berhasil ditambahkan.",$request->all());
	}
	public function deleteComment(Request $request){

		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'type' => "required|in:comment,reply_comment",
			'comment_id' => "required_if:type,==,comment",
			'reply_id' => "required_if:type,==,reply_comment"
		];
		if($request->type =="comment"){
			$save = $this->actionServices->deleteComment($request->comment_id);
		}else{
			$save = $this->actionServices->deleteReplyComment($request->reply_id);
		}
		if(!$save)
			return $this->services->response(503,"Server Error!");
		 
		return $this->services->response(200,"Komentar berhasil dihapus.",$request->all());
	}
}
