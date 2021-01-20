<?php

namespace App\Http\Controllers\API\Dashboard\Location;

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

    public function get_country(){
        $data = CountryModel::select('*')->get();
		return $this->services->response(200,"Country List",$data);
    }

    public function get_province(){
        $data = ProvinceModel::select('id_prov','nama as name')->get();
		return $this->services->response(200,"Province List",$data);
    }

    public function get_city($id){
        $data = CityModel::select('id_kab as city_id','nama as name')->where('id_prov', $id)->get();
		return $this->services->response(200,"City List",$data);
    }

    public function get_district($id){
        $data = DistrictModel::select('id_kec as district_id','nama as name')->where('id_kab', $id)->get();
		return $this->services->response(200,"District List",$data);
    }

    public function get_subDistrict($id){
        $data = SubDistrictModel::select('id_kel as sub_district_id','nama as name')->where('id_kec', $id)->get();
		return $this->services->response(200,"Subdistrict List",$data);
    }
}
