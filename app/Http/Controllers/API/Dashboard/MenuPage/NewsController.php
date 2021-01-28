<?php

namespace App\Http\Controllers\API\Dashboard\MenuPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NewsModel;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\Fase2\NewsCommentModel;
use App\Models\Fase2\NewsCommentReplyModel;

class NewsController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->newsModel = NewsModel::select('*');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getNews = $this->getDataServices->getNews();

        if (!empty($getNews)) {
            $action = $this->actionServices->getactionrole($checkUser->role_id, 'news');
            return $this->actionServices->response(200,"News and article",$getNews, $action);
        }else{
            return $this->services->response(404,"News and article doesnt exist!");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $rules = [
            'news_title' => "required",
            'news_type_id' => "required",
            'news_details' => "required",
            'news_photo' => "required|image|mimes:jpg,png,jpeg|max:5000"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }

        $postData = ['news_id' => $this->services->randomid(4),
                     'news_title' => $request->news_title,
                     'news_type_id' => $request->news_type_id,
                     'news_details' => $request->news_details,
                     'news_url' => $request->news_url];

        if($request->hasfile('news_photo')){
            $file = $request->file('news_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = "news_".round(microtime(true)).'.'.$extension;
            $destinationPath = public_path('/uploads/news/');
            $file->move($destinationPath, $filename);

            $postData['news_photo'] = $filename;
            
        }
        
        $saved = NewsModel::create($postData);

        if(!$saved){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Create news success",$postData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getNews = $this->getDataServices->getNews($request->byNewsid);

        if (!empty($getNews)) {
            return $this->services->response(200,"News and article",$getNews);
        }else{
            return $this->services->response(404,"News and article doesnt exist!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $rules = [
            'news_title' => "required",
            'news_type_id' => "required",
            'news_details' => "required",
            'news_photo' => "image|mimes:jpg,png,jpeg|max:5000"
		];
		$checkValidate = $this->services->validate($request->all(),$rules);

		if(!empty($checkValidate)){
			return $checkValidate;
        }

        $newsData = NewsModel::where('news_id',$request->byNewsid)->first();

        if(!$newsData){
            return $this->actionServices->response(404,"News doesnt exist!");
        }

        $postData = ['news_title' => $request->news_title,
                     'news_type_id' => $request->news_type_id,
                     'news_details' => $request->news_details,
                     'news_url' => $request->news_url];

        if(!empty($request->news_photo)){
            $file = $request->file('news_photo');
            $name_file = $file->getClientOriginalName();  
        }

        if($request->news_photo != '' && $name_file != $newsData->news_photo){
            $folder = public_path().'/uploads/news/';

            if($newsData->news_photo != '' && $newsData->news_photo != null){
                $file_old = $folder.$newsData->news_photo;
                unlink($file_old);
            }  
            $extension = $file->getClientOriginalExtension();
            $filename = "news_".round(microtime(true)).'.'.$extension;
            $file->move($folder, $filename);

            $postData['news_photo'] = $filename;
        }
        
        $updateNews = NewsModel::where('news_id', $request->byNewsid)->update($postData); 
        if(!$updateNews){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Create news success",$postData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $newsData = NewsModel::where('news_id',$request->byNewsid)->first();
        if($newsData){
            if($newsData->news_photo != '' && $newsData->news_photo != null){
                $file_old = public_path().'/uploads/news/'.$newsData->news_photo;
                if(!empty($file_old)){
                    unlink($file_old);
                }
            }
        }else{
            return $this->actionServices->response(404,"News doesnt exist!");
        }

        $delete = NewsModel::where('news_id', $request->byNewsid)->delete();
        if(!$delete){
			return $this->services->response(503,"Server Error!");
        }

        return $this->services->response(200,"You have been successfully delete",$delete);
    }

    public function getNewstype(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $getNewstype = $this->getDataServices->getNewsType();

        if (!empty($getNewstype)) {
            return $this->services->response(200,"News type",$getNewstype);
        }else{
            return $this->services->response(404,"News type doesnt exist!");
        }
    }

    public function addComment(Request $request){
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        
		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $rules = [
			'news_id' => "required",
			'comment' => "required|string",
			'attachment' => "nullable|string",
			'desc' => "nullable|string"
        ];
        
        $postParam = array(
			'news_id' => $request->news_id,
			'user_id' =>  1,
            'comment' => $request->comment,
        );
        
        $saved_comment = NewsCommentModel::create($postParam);
        
        if (!empty($saved_comment)) {
            return $this->services->response(200,"Add comment successfuly",$postParam);
        }else{
            return $this->services->response(404,"Add comment failed");
        }
    }

    public function addReplyComment(Request $request){
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        
		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $rules = [
            'comment_id'    => "required",
			'comment'       => "required|string",
			'attachment'    => "nullable|string",
			'desc'          => "nullable|string"
        ];
        
        $postParam = array(
			'comment_id'    => $request->comment_id,
            'comment_by'    =>  1,
            'reply_by'      =>  1,
            'comment'       => $request->comment,
        );
        
        $saved_comment = NewsCommentReplyModel::create($postParam);
        
        if (!empty($saved_comment)) {
            return $this->services->response(200,"Add reply comment successfuly",$postParam);
        }else{
            return $this->services->response(404,"Add reply comment failed");
        }
    }

    public function deleteComment(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);
        
		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $Comment = NewsCommentModel::where('comment_id', $request->byComment_id)->delete(); 
        if(!$Comment){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Delete comment successfully !", $Comment);
    }

    public function deleteReplyComment(Request $request){

        $checkUser = $this->getDataServices->getAdminbyToken($request);
        
		if (!$checkUser){
            return $this->actionServices->response(406,"User doesnt exist!");
        }

        $replyComment = NewsCommentReplyModel::where('reply_id', $request->byReply_id)->delete(); 
        if(!$replyComment){
			return $this->services->response(503,"Server Error!");
        }
        
        return $this->services->response(200,"Delete reply comment successfully !", $replyComment);
    }
}
