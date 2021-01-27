<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel;
use App\Models\Dashboard\RolesPermissionsModel;

class RolesModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_user_roles';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'role_name', 'role_access'
    ];
    /**
     * Searchable fields
     * @var array
     */
    protected $searchable = [
        'role_name', 'company_id'
    ];


    /**
     * Select all fields
     * @param $query
     */
    public function scopeSelectAll($query)
    {
        $query->selectRaw("role_id, company_id, role_name, role_access, role_resources, created_at");
    }
    
    /**
     * Related to permission
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function permission()
    {
        return $this->hasMany(RolesPermissionsModel::class, 'role_id', 'role_access')
            ->join('xin_permissions', 'xin_permissions.id', '=', 'xin_roles_permissions.permission_id')
            ->join('xin_menus', 'xin_menus.id', '=', 'xin_permissions.menu_id')
            ->selectRaw("
                xin_permissions.id as permission_id,
                xin_roles_permissions.role_id,
                xin_permissions.menu_id as menu_id,
                xin_menus.name as menu_name,
                xin_menus.icon as menu_icon,
                xin_menus.initial as menu_initial
            ")
            ->groupBy('xin_permissions.menu_id');
    }
}
