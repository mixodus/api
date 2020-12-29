<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fase2\CountryModel;
use App\Models\Fase2\CityModel;
use App\Models\Fase2\ProvinceModel;
use App\Models\Fase2\DistrictModel;
use App\Models\Fase2\SubDistrictModel;
use App\Http\Controllers\Services\GeneralServices;

class LocationController extends Controller
{
    public function __construct(){
        $this->services = new GeneralServices();
    }
    public function country(){
        $data = CountryModel::select('*')->get();
		return $this->services->response(200,"Daftar Negara",$data);
    }
    public function province(){
        $data = ProvinceModel::select('id_prov','nama as name')->get();
		return $this->services->response(200,"Daftar Provinsi",$data);
    }
    public function city(){
        $data = CityModel::select('id_kab as city_id','nama as name')->get();
		return $this->services->response(200,"Daftar Kota",$data);
    }
    public function district(){
        $data = DistrictModel::select('id_kec as district_id','nama as name')->get();
		return $this->services->response(200,"Daftar Kecamatan",$data);
    }
    public function subDistrict(){
        $data = SubDistrictModel::select('id_kel as sub_district_id','nama as name')->get();
		return $this->services->response(200,"Daftar Kelurahan",$data);
    }
}
