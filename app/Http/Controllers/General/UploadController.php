<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function index(){
        $data['title'] = "Upload Resume";
        return view('general.upload', $data);
    }
    public function upload(Request $request){
        // if($request->userfile == null){
		// 	return $this->services->response(503,"File tidak ditemukan!");
		// }
		$image = $request->file('userfile');
		$imgname = $image->getClientOriginalName();
		$destinationPath = public_path('/uploads/certification/');
		$file->move($destinationPath, $imgname);
		
		$request['certification_file'] = $imgname;
		$request['id'] = $id;
		$upload = $this->actionServices->updateCertificationfile($request->all());
		if(!$upload){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		return $this->services->response(200,"File berhasil diunggah",$request->all());
	}
}
