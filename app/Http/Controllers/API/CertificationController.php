<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\ActionServices;
use App\Http\Controllers\Services\GetDataServices;
use App\Models\ActivitiesPointModel;

class CertificationController extends Controller
{
    public function __construct(){
		$this->services = new GeneralServices();
		$this->actionServices = new ActionServices();
		$this->getDataServices = new GetDataServices();
		$this->activity_point = ActivitiesPointModel::select('*');
	}
    public function index(Request $request){
        $checkUser = $this->getDataServices->getUserbyToken($request);
		$getCeriticationUser = $this->getDataServices->getCertification($checkUser->user_id);
        
		return $this->services->response(200,"Certification",$getCeriticationUser);
	}
	
	public function detail(Request $request,$id){
        $checkUser = $this->getDataServices->getUserbyToken($request);
		$getCeriticationUser = $this->getDataServices->getCertification($checkUser->user_id,$id);
        if (!$getCeriticationUser->isEmpty()) {
			return $this->services->response(200,"Certification Detail",$getCeriticationUser);
		}else{
			return $this->services->response(404,"Certification not found!");
		}
    }
    public function postData(Request $request){
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$rules = [
			'title' => "required|string",
			'description' => "required|string",
			'certification_date' => "required",
		];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		if(empty($request['id']) AND $request['id'] == null){
			$save = $this->actionServices->saveCertification($request->all(),$checkUser->user_id);
			if(!$save){
				return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
			} 
			$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Sertifikasi berhasil ditambahkan');
			$getPoint = $this->activity_point->where('activity_point_code', 'add_certification')->first();
			if($getPoint) {
				$save_trx_point = $this->actionServices->postTrxPoints("add_certification",$getPoint->activity_point_point,$checkUser->user_id,0,1);
			}
			return $this->services->response(200,"Sertifikasi berhasil ditambahkan",$save);
		}else{
			$save = $this->actionServices->updateCertification($request->all(),$checkUser->user_id);
			if(!$save){
				return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
			} 
			return $this->services->response(200,"Sertifikasi berhasil diperbarui",$request->all());
		}
    }
	public function upload(Request $request,$id){
        if($request->userfile == null){
			return $this->services->response(503,"File tidak ditemukan!");
		}
		$request['id'] = $id;

		$image = $request->file('userfile');
		$imgname = $image->getClientOriginalName();
		$destinationPath = public_path('/uploads/certification/');
		$image->move($destinationPath, $imgname);
		
		$request['certification_file'] = $imgname;
		$upload = $this->actionServices->updateCertificationfile($request->all());
		if(!$upload){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		return $this->services->response(200,"File berhasil diunggah",$request->all());
	}
    public function delete(Request $request){
		$rules = ['id' => "required"];
		$checkValidate = $this->services->validate($request->all(),$rules);
	
		if(!empty($checkValidate)){
			return $checkValidate;
		}
		$checkUser = $this->getDataServices->getUserbyToken($request);
		$getCeriticationUser = $this->getDataServices->getCertification($checkUser->user_id,$request->id);
        if (!$getCeriticationUser->isEmpty()) {
			if($getCeriticationUser[0]['certification_name'] != "" && !empty($getCeriticationUser[0]['certification_name'])){
				unlink(public_path('uploads/certification/'.$getCeriticationUser[0]['certification_name']));
			}
        }
        
		$save = $this->actionServices->deleteEmployeeCertification($request->id);
		if(!$save){
			return $this->services->response(503,"Terjadi Kesalahan Jaringan!");
		} 
		$save_notif = $this->actionServices->postNotif(5,0,$checkUser->user_id,'Sertifikat telah berhasil dihapus');
		
		return $this->services->response(200,"Sertifikat berhasil dihapus.",array());
	}
}
