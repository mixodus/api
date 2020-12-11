<?php

namespace App\Http\Controllers\API\dashboard\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GetDataServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\Dashboard\MenuModel;
use App\Models\Dashboard\RolesPermissionsModel;
use App\Models\Dashboard\PermissionsModel;
use App\Models\RolesModel;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->services = new GeneralServices();
        $this->getDataServices = new GetDataServices();
        $this->middleware('permission:permission-view', ['only' => 'index']);
        $this->middleware('permission:permission-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MenuModel::get();
        foreach ($data as $parent) {
                $list_permission = PermissionsModel::leftjoin('xin_menus', 'xin_menus.id', '=', 'xin_permissions.menu_id')
                    ->where('xin_permissions.menu_id', $parent->id)
                    ->select('xin_permissions.action', 'xin_permissions.id', 'xin_permissions.menu_id')
                    ->get()->toArray();
                    
                $parent->action = $list_permission;
        }

        if ($data) {
            return $this->services->response(200, "Permission List", $data);
        } else {
            return $this->services->response(404,"Get permission list failed !");
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
        $rules =  [
            'name' => 'required|string',
            'actions' => 'required|array'
        ];

        $checkValidate = $this->services->validate($request->all(),$rules);
        if(!empty($checkValidate)){
			return $checkValidate;
        }

        \DB::beginTransaction();
        try {
            $name = $request->name;
            $actions = $request->actions;

            foreach ($actions as $key => $value){
                $name_permission = str_slug($name)."-".$value;
                // check existing actions
                $check_exist = PermissionsModel::where("name", $name_permission)
                    ->first();
                if ($check_exist){
                    throw new \Exception("Permission is already exist");
                }
                // available actions
                $arr = ['view', 'add', 'edit', 'delete'];
                $check = in_array($value, $arr);
                if (!$check){
                    throw new \Exception("Wrong actions");
                }
                // insert into table permission
                $insert = new PermissionsModel();
                $insert->name = $name_permission;
                $insert->group_name = str_slug($name);
                $insert->action = $value;
                $insert->save();
                if (!$insert){
                    throw new \Exception("Failed to insert data");
                }
            }
            \DB::commit();
            return $this->services->response(200, "Create permission success");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->services->response(404, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {       
        $data = PermissionsModel::selectAll()
            ->where("id", $request->id)
            ->get();


        if ($data) {
            return $this->services->response(200, "Permission show", $data);
        } else {
            return $this->services->response(404,"show permission list failed !");
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
    public function update(Request $request, $id)
    {
        $rules =  [
            'name' => 'required|string',
            'actions' => 'required|array'
        ];

        $checkValidate = $this->services->validate($request->all(),$rules);
        if(!empty($checkValidate)){
			return $checkValidate;
        }

        \DB::beginTransaction();
        try {
            $name = $request->name;
            $actions = $request->actions;

            $check_menuid = PermissionsModel::where("group_name", str_slug($name))->select('menu_id')
                ->first();
            $check_exist = PermissionsModel::where("group_name", str_slug($name))
                ->delete();
                
            foreach ($actions as $key => $value){
                $name_permission = str_slug($name)."-".$value;
                // available actions
                $arr = ['view', 'add', 'edit', 'delete'];
                $check = in_array($value, $arr);
                if (!$check){
                    throw new \Exception("Wrong actions");
                }
                // insert into table permission
                $insert = new PermissionsModel();
                $insert->name = $name_permission;
                $insert->group_name = str_slug($name);
                $insert->menu_id = $check_menuid->menu_id;
                $insert->action = $value;
                $insert->save();
                if (!$insert){
                    throw new \Exception("Failed to insert data");
                }
            }
            \DB::commit();
            return $this->services->response(200, "Update permission success");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->services->response(404, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            \DB::commit();
            $check = RolesPermissionsModel::where("permission_id", $id)->first();
            if(empty($check)){
                throw new \Exception("Cannot delete data, permission is being used by user group");
            }
            $delete = PermissionsModel::where("id", $id)->delete();
            return $this->services->response(200, "Delete permission success");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->services->response(404, $e->getMessage());
        }
    }
}
