<?php

namespace App\Http\Controllers\API\Dashboard\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\GetDataServices;
use App\Http\Controllers\Services\GeneralServices;
use App\Models\Dashboard\MenuModel;
use App\Models\Dashboard\RolesModel;
use App\Models\Dashboard\PermissionsModel;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->services = new GeneralServices();
        $this->getDataServices = new GetDataServices();
        $this->middleware('permission:menu-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        $class = $request->class;

        if($class){
            $menu = MenuModel::getAvailableMenu()->toArray();
            $data = MenuModel::select('xin_menus.id', 'xin_menus.name', 'xin_menus.icon', 'xin_menus.url', 'xin_menus.type', 'xin_menus.is_parent',
            'xin_menus.parent_id', 'xin_menus.level', 'xin_menus.group_name', 'xin_menus.initial', 'xin_menus.class', 'xin_menus.status')
                ->join("xin_permissions", "xin_permissions.menu_id", '=', 'xin_menus.id')
                ->join("xin_roles_permissions", "xin_roles_permissions.permission_id", '=', 'xin_permissions.id')
                ->where("xin_menus.status", 1)
                ->where('xin_permissions.action', "view")
                ->where('class', $class)
                ->where("status", 1)
                ->orderby('xin_menus.id', 'asc')
                ->get();
        }else{
            $menu = MenuModel::getAvailableMenu($checkUser->role_id)->toArray();
            $data = MenuModel:: select('xin_menus.id', 'xin_menus.name', 'xin_menus.icon', 'xin_menus.url', 'xin_menus.type', 'xin_menus.is_parent',
            'xin_menus.parent_id', 'xin_menus.level', 'xin_menus.group_name', 'xin_menus.initial', 'xin_menus.class', 'xin_menus.status')
                ->join("xin_permissions", "xin_permissions.menu_id", '=', 'xin_menus.id')
                ->join("xin_roles_permissions", "xin_roles_permissions.permission_id", '=', 'xin_permissions.id')
                ->where("xin_menus.status", 1)
                ->where('xin_permissions.action', "view")
                ->where("xin_roles_permissions.role_id", $checkUser->role_id)
                ->orderby('xin_menus.id', 'asc')
                ->get();
        }

        if ($data){
            $data_child = [];
            $iter = 0;
            foreach ($data as $key => $value){
                $children = MenuModel::getChild($value->id, $menu);
                if($value->type == "collapsable" && count($children) > 0){
                    $data_child[$iter]['id'] = (string)$value->id;
                    $data_child[$iter]['title'] = $value->name;
                    $data_child[$iter]['icon'] = $value->icon;
                    $data_child[$iter]['url'] = $value->url;
                    $data_child[$iter]['is_parent'] = $value->is_parent;
                    $data_child[$iter]['parent_id'] = $value->parent_id;
                    $data_child[$iter]['level'] = $value->level;
                    $data_child[$iter]['type'] = $value->type == "collapsable" ? "group" : $value->type;
                    $data_child[$iter]['group_name'] = $value->group_name;
                    $data_child[$iter]['children'] = $children;
                    $iter++;
                }else{
                    $data_child[$iter]['id'] = (string)$value->id;
                    $data_child[$iter]['title'] = $value->name;
                    $data_child[$iter]['icon'] = $value->icon;
                    $data_child[$iter]['url'] = $value->url;
                    $data_child[$iter]['is_parent'] = $value->is_parent;
                    $data_child[$iter]['parent_id'] = $value->parent_id;
                    $data_child[$iter]['level'] = $value->level;
                    $data_child[$iter]['type'] = $value->type == "collapsable" ? "group" : $value->type;
                    $data_child[$iter]['group_name'] = $value->group_name;
                    $data_child[$iter]['children'] = $children;
                    $iter++;
                }
            }
            if(empty($data_child)){
                return $this->services->response(400,"Menu not available. Check class name Please Try Again !");
            }
            return $this->services->response(200, "Menu list", $data_child);
        }else{
            return $this->services->response(404,"Get menu failed !");
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
            'icon' => 'required|string',
            'url' => 'required|string|max:100',
            'type' => 'required|in:group,collapsable,item,link',
            'permissions' => 'required|array',
            'initial' => 'string',
        ];
        $checkValidate = $this->services->validate($request->all(),$rules);
        if(!empty($checkValidate)){
			return $checkValidate;
        }
        
        if (empty($request->is_parent)){
            $rulesChild =  [
                'parent_id' => 'required',
                'level' => 'required|integer',
            ];
            $checkValidateChild = $this->services->validate($request->all(),$rulesChild);
            if(!empty($checkValidateChild)){
                return $checkValidateChild;
            }
        }

        \DB::beginTransaction();
        try {
            $name = $request->name;
            $icon = $request->icon;
            $url = $request->url;
            $level = $request->level;
            $parent = $request->is_parent;
            $parent_id = $request->parent_id;
            $type = $request->type;
            $permissions = $request->permissions;
            $initial = str_replace(" ","-",$request->initial);
            $group = explode(' ',trim($name));

            if ($parent == true){
                $params = [
                    "name" => $name,
                    "icon" => $icon,
                    "url" => $url,
                    "level" => 0,
                    "type" => $type,
                    "status" => 1,
                    "group_name" => str_slug($name),
                    "initial" => str_replace(" ","-",$request->initial),
                    "class" => "super admin"
                ];
                $group_name = str_slug($name);
            }else{
                $data_parent = MenuModel::where("id", $parent_id)->first();
                if(!$data_parent){
                    throw new \Exception("Parent id not found");
                }
                $params = [
                    "name" => $name,
                    "icon" => $icon,
                    "url" => $url,
                    "level" => $level,
                    "type" => $type,
                    "parent_id" => $parent_id,
                    "status" => 1,
                    "group_name" => $data_parent->group_name,
                    "initial" => str_replace(" ","-",$request->initial),
                    "class" => "super admin"
                ];
                $group_name = $data_parent->group_name;
            }
            $insert = new MenuModel();
            $insert->insert($params);
            if (!$insert){
                throw new \Exception("Failed to insert data");
            }
            // insert permission
            foreach ($permissions as $key => $value){
                $menu = MenuModel::select("id")->orderBy("id", "desc")->first();
                $params_permission = [
                    "name" => $initial."-".$value,
                    "group_name" => $group_name,
                    "menu_id" => $menu->id,
                    "action" => $value
                ];
                $insert_permission = new PermissionsModel();
                $insert_permission->insert($params_permission);
                if (!$insert_permission){
                    throw new \Exception("Failed to insert data permission");
                }
            }
            \DB::commit();
            return $this->services->response(200, "Insert Menu Success");
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
        $checkUser = $this->getDataServices->getAdminbyToken($request);
        $data = MenuModel::selectAll()
            ->where("id", $request->menu_id)
            ->first();

        $menu = MenuModel::getAvailableMenu($checkUser->role_id);
        
        if (!empty($data)) {
            $data['child'] = MenuModel::getChild($request->menu_id, $menu);
            // $this->data = $data;
            return $this->services->response(200,"Success", $this->data = $data);
        }else{
            return $this->services->response(404, "Menu not found !");
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
            'icon' => 'required|string',
            'url' => 'required|string|max:100',
            'type' => 'required|in:group,collapsable,item',
        ];
        $checkValidate = $this->services->validate($request->all(),$rules);
        if(!empty($checkValidate)){
			return $checkValidate;
        }
        
        if (empty($request->is_parent)){
            $rulesChild =  [
                'parent_id' => 'required',
                'level' => 'required|integer',
            ];
            $checkValidateChild = $this->services->validate($request->all(),$rulesChild);
            if(!empty($checkValidateChild)){
                return $checkValidateChild;
            }
        }

        \DB::beginTransaction();
        try {
            $name = $request->name;
            $icon = $request->icon;
            $url = $request->url;
            $level = $request->level;
            $parent = $request->is_parent;
            $parent_id = $request->parent_id;
            $type = $request->type;
            $group = explode(' ',trim($name));
            $permissions = $request->permissions;

            if ($parent == 0){
                $params = [
                    "name" => $name,
                    "icon" => $icon,
                    "url" => $url,
                    "level" => 0,
                    "type" => $type,
                    "status" => 1,
                    "group_name" => str_slug($group[0]),
                ];
                $group_name = str_slug($name);
            }else{
                $data_parent = MenuModel::where("id", $parent_id)->first();
                if(!$data_parent){
                    throw new \Exception("Parent id not found");
                }
                $params = [
                    "name" => $name,
                    "icon" => $icon,
                    "url" => $url,
                    "level" => $level,
                    "type" => $type,
                    "parent_id" => $parent_id,
                    "status" => 1,
                    "group_name" => $data_parent->group_name,
                ];
                $group_name = $data_parent->group_name;
            }
            $update = MenuModel::where('id', $id)->update($params);
            if (!$update){
                throw new \Exception("Failed to update data");
            }

            // insert permission
            if ($permissions){
                foreach ($permissions as $key => $value){
                    $menu = MenuModel::select("id")->orderBy("id", "desc")->first();
                    $permission_name = $group_name."-".$value;
                    $params_permission = [
                        "name" => $permission_name,
                        "group_name" => $group_name,
                        "menu_id" => $menu->id,
                        "action" => $value
                    ];

                    $check_permission = PermissionsModel::where('menu_id', $menu->id)
                        ->where("name", $permission_name)
                        ->first();
                    if (!$check_permission){
                        $insert_permission = new PermissionsModel();
                        $insert_permission->insert($params_permission);
                        if (!$insert_permission){
                            throw new \Exception("Failed to insert data permission");
                        }
                    }
                }
            }
            \DB::commit();
            return $this->services->response(200, "Update Menu Success");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->services->response(404,$e->getMessage());
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
        //
    }
}
