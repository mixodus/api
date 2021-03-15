<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppVersionModel extends Model
{
    use SoftDeletes;

	protected $table = 'xin_app_version';
	public $primarykey = 'app_version_id';
	
	public $timestamps = true;
	protected $fillable = [
		'version',
		'url_update',
		'is_force'
	];
	protected $hidden = ['updated_at','deleted_at'];
}
