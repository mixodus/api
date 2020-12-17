<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class ActivityLogModel extends Model
{
	protected $table = 'xin_activity_log';
	public $primarykey = 'log_id';
	
	public $timestamps = true;
	protected $fillable = [
		'endpoint',
		'user_id', 
		'version',
		'request',
		'header',
		'result',
		'status',
		'ip_address',
	];
	protected $hidden = ['created_at', 'deleted_at','updated_at'];
	
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'user_id','user_id');
	}
}
