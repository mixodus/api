<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dashboard\RolesPermissionsModel;
use App\Models\Dashboard\PermissionsModel;

class MenuModel extends Model
{
 
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_menus';

    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        "name",
        "icon",
        "url",
        "is_parent",
        "parent_id",
        "level",
        "type",
        "class",
        "initial",
        "group_name",
        "status"
    ];
    
    /**
     * Select all fields
     * @param $query
     */
    public function scopeSelectAll($query)
    {
        $query->selectRaw("id, name, icon, url, is_parent, parent_id, level, type, class, group_name");
    }

    /**
     * Get child from menu
     * @param $parent_id
     * @param $id
     * @return mixed
     */
    public static function getChild($parent_id, $menu)
    {
        $data = self::where("parent_id", $parent_id)->orderBy("level", "asc")
            ->whereIn("id", $menu)
            ->where("status", 1)
            ->get();

        if ($data->count() > 0){
            foreach ($data as $key => $value){
                if ($value->is_parent == 1){
                    $result[$key]["id"] = (string)$value->id;
                    $result[$key]["title"] = $value->name;
                    $result[$key]["icon"] = $value->icon;
                    $result[$key]["url"] = $value->url;
                    $result[$key]["is_parent"] = $value->is_parent;
                    $result[$key]["parent_id"] = $value->parent_id;
                    $result[$key]["level"] = $value->level;
                    $result[$key]["type"] = $value->type;
                    $result[$key]["group_name"] = $value->group_name;
                    $result[$key]["children"] = self::getChild($value->id, $menu);
                }else{
                    $result[$key]["id"] = (string)$value->id;
                    $result[$key]["title"] = $value->name;
                    $result[$key]["icon"] = $value->icon;
                    $result[$key]["url"] = $value->url;
                    $result[$key]["is_parent"] = $value->is_parent;
                    $result[$key]["parent_id"] = $value->parent_id;
                    $result[$key]["level"] = $value->level;
                    $result[$key]["type"] = $value->type;
                    $result[$key]["group_name"] = $value->group_name;
                    $result[$key]["children"] = self::getChild($value->id, $menu);
                }
            }
        }else{
            $result = [];
        }

        return $result;

    }

    /**
     * Get available menu for current user
     * @param $role_id
     * @return mixed
     */
    public static function getAvailableMenu($role_id=false)
    {
        $data = RolesPermissionsModel::selectRaw("xin_roles_permissions.*, xin_permissions.name, xin_permissions.menu_id")
                ->join("xin_permissions", "xin_permissions.id", '=', 'xin_roles_permissions.permission_id');

                if($role_id !== false){
                    $data = $data->where("role_id", $role_id);
                }
                
        $data = $data->groupBy("xin_permissions.menu_id")->pluck("xin_permissions.menu_id");
        return $data;
    }
}