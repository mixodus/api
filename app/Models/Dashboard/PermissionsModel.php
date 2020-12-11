<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dashboard\RolesPermissionsModel;
class PermissionsModel extends Model
{
 /**
     * Table Name
     * @var string
     */
    protected $table = 'xin_permissions';

    public $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "group_name",
        "menu_id",
        "action",
    ];	

    /**
     * Searchable fields
     * @var array
     */
    protected $searchable = ["name", "group_name"];

    /**
     * Select all fields
     * @param $query
     */
    public function scopeSelectAll($query)
    {
        $query->selectRaw("id, name, group_name, menu_id, action");
    }   //
    
}
