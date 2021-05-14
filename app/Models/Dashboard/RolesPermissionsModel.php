<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class RolesPermissionsModel extends Model
{
 /**
     * Table name
     * @var string
     */
    protected $table = 'xin_roles_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "permission_id",
        "role_id"
    ];	

    public $timestamps = false;   //
}
