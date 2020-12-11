<?php

namespace App\Http\Controllers\API\dashboard\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GetDataServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Http\Controllers\Services\Dashboard\ActionServices;
use App\Models\Dashboard\MenuModel;
use App\Models\Dashboard\RolesPermissionsModel;
use App\Models\Dashboard\PermissionsModel;
use App\Models\RolesModel;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->services = new GeneralServices();
        $this->getDataServices = new GetDataServices();
        $this->getAction = new ActionServices();
        $this->middleware('permission:roles-view', ['only' => 'index']);
        $this->middleware('permission:roles-add', ['only' => ['create', 'store']]);
        // $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-delete', ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        $data = RolesModel::paginate();
        foreach ($data as $role) {
            foreach ($role->permission as $permission) { 
                $list_permission = PermissionsModel::selectRaw('xin_permissions.id, xin_permissions.action')
                    ->join('xin_roles_permissions', 'xin_roles_permissions.permission_id', '=', 'xin_permissions.id')
                    ->where('xin_permissions.menu_id', $permission->menu_id)
                    ->where('xin_roles_permissions.role_id', $role->role_access)
                    ->get();
                    
                $permission->action = $list_permission;
            }
        }

        if ($data) {
            $action = $this->getAction->getactionrole($checkUser->role_id, 'roles');
            return $this->getAction->response(200, "Role List", $data, $action);
        } else {
            return $this->services->response(404,"Get role list failed !");
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
        if ($checkUser->role_id != 1) {
            return $this->show_error("Unauthorized User");
        }

        $rules =  [
            'company_id' => 'required|integer',
            'role_name' => 'required|string|min:2|max:50',
            'permissions' => 'required|array'
        ];
        $checkValidate = $this->services->validate($request->all(),$rules);
        if(!empty($checkValidate)){
			return $checkValidate;
        }

        $data = $request->only(['company_id', 'role_name']);

        \DB::beginTransaction();
        try {
            $permissions = $request->permissions;
            $insert = RolesModel::create($data);
            if (!$insert) {
                throw new \Exception("Failed to insert data");
            }
            foreach ($permissions as $key => $value) {
                $menu_id = $value['menu_id'];
                $actions = $value['actions'];
                // foreach ($actions as $k => $v) {
                    // menu
                    $menu = MenuModel::where("id", $menu_id)->first();
                    if (!$menu) {
                        throw new \Exception("Wrong menu id");
                    }
                    $name = $menu->initial;
                    $permission_name = $name . "-" . $actions;
                    // check for permission id
                    $check = PermissionsModel::select("id")->where("menu_id", $menu_id)->where("name", $permission_name)->first();
                    if (!$check) {
                        throw new \Exception("Wrong permission id");
                    }
                    // insert permission into user group
                    $insert_permission = new RolesPermissionsModel();
                    $insert_permission->role_id = $insert->id;
                    $insert_permission->permission_id = $check->id;
                    $insert_permission->save();
                    if (!$insert_permission) {
                        throw new \Exception("Failed to update data");
                    }
                // }
            }
            \DB::commit();
            return $this->services->response(200, "Create role success");
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
        $data = RolesModel::where("role_id", $request->role_id)->get();
        foreach ($data as $role) {
            foreach ($role->permission as $permission) { 
                $list_permission = PermissionsModel::selectRaw('xin_permissions.id, xin_permissions.action')
                    ->join('xin_roles_permissions', 'xin_roles_permissions.permission_id', '=', 'xin_permissions.id')
                    ->where('xin_permissions.menu_id', $permission->menu_id)
                    ->where('xin_roles_permissions.role_id', $role->role_access)
                    ->where('xin_roles_permissions.role_id', $request->role_id)
                    ->get()->toArray();
                    
                $permission->action = $list_permission;
            }
        }
        if ($data) {
            return $this->services->response(200, "Role show", $data);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Failed to load data"
            ]);
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $rolePermissions = RolesPermissionsModel::where("role_id",$request->role_id)
            ->select('permission_id')
            ->get();
            
        if(!$rolePermissions){
            foreach($rolePermissions as $rolePermission){
                $data[]= $rolePermission['permission_id'];
            }
            
            if ($data) {
                return $this->services->response(200, "Role Permission", $data);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Failed to load data"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "data not found",
                'data' => ''
            ]);
        }
        
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
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        if ($checkUser->role_id != 1) {
            return $this->show_error("Unauthorized User");
        }

        $rules =  [
            'company_id' => 'required|integer',
            'role_name' => 'required|string|min:2|max:50',
            'permissions' => 'required|array'
        ];
        $checkValidate = $this->services->validate($request->all(),$rules);
        if(!empty($checkValidate)){
			return $checkValidate;
        }

        \DB::beginTransaction();
        try {
            $permission_id = $request->permissions;

            // $update = RolesModel::where('role_id', $id)
            //     ->update([
            //         'company_id' => $request->company_id,
            //         'role_name' => $request->role_name
            //     ]);
            // if (!$update) {
            //     throw new \Exception("Failed to update data");
            // }

            if ($permission_id) {
                // delete all permission from user group
                $delete = RolesPermissionsModel::where("role_id", $id)
                    ->delete();

                foreach ($permission_id as $value) {
                    $menu_id = $value['menu_id'];
                    $actions = $value['action'];
                    
                    // foreach ($actions as $k => $v) {
                        // menu
                        $menu = MenuModel::where("id", $menu_id)->first();

                        if (!$menu) {
                            throw new \Exception("Wrong menu id");
                        }
                        $name = $menu->initial;
                        $permission_name = $name . "-" . $actions;
                        
                        // check for permission id
                        $check = PermissionsModel::select("id")->where("menu_id", $menu_id)->where("name", $permission_name)->first();
                        
                        if (!$check) {
                            throw new \Exception("Wrong permission id");
                        }
                        // insert permission into user group
                        $insert = new RolesPermissionsModel();
                        $insert->role_id = $id;
                        $insert->permission_id = $check->id;
                        $insert->save();
                        if (!$insert) {
                            throw new \Exception("Failed to update data");
                        }
                    // }
                }
            }

            \DB::commit();
            return $this->services->response(200, "Update role success");
        } catch (\Exception $e) {
            return $e;
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
    public function destroy(Request $request,$id)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        if ($checkUser->role_id != 1) {
            return $this->show_error("Unauthorized User");
        }

        \DB::beginTransaction();
        try {
            $delete = RolesModel::where('role_id', $id)
                ->delete();
            if (!$delete) {
                throw new \Exception("Failed to delete data");
            }
            $delete_permission = RolesPermissionsModel::where('role_id', $id)->delete();
            if (!$delete_permission) {
                throw new \Exception("Failed to delete data permission");
            }
            \DB::commit();
            return $this->services->response(200, "Delete role success");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->services->response(404, $e->getMessage());
        }
    }
}
