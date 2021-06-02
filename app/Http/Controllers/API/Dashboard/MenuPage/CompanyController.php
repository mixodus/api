<?php

namespace App\Http\Controllers\API\Dashboard\MenuPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\Dashboard\GetDataServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;

class CompanyController extends Controller
{
    public function __construct(){
        $this->getDataServices = new GetDataServices();
        $this->services = new GeneralServices();
        $this->getAction = new ActionServices();
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
            return $this->getAction->response(406,"User doesnt exist!");
        }
            
            
        $data_company = CompanyModel::select('*')->get();

        if ($data_company) {
            // $action = $this->getAction->getactionrole($checkUser->role_id, 'jobs');
            return $this->getAction->response(200, "Jobs List", $data_company);
        } else {
            return $this->services->response(404,"Get Jobs list failed !");
        }
    }
    public function getCompanyByID(Request $request, $id){
        $checkUser = $this->getDataServices->getAdminbyToken($request);

		if (!$checkUser){
            return $this->getAction->response(406,"User doesnt exist!");
        }
        $data_company = CompanyModel::select('*')->where('company_id', $id)->first();

        if ($data_company) {
            // $action = $this->getAction->getactionrole($checkUser->role_id, 'jobs');
            return $this->getAction->response(200, "Company", $data_company);
        } else {
            return $this->services->response(404,"Get Company failed !");
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
