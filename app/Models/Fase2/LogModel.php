<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    protected $table = 'xin_log';
	public $primarykey = 'id';
	
	public $timestamps = true;
	protected $fillable = [
		'server_type', 
		'type',
		'name',
		'user_id',
		'version',
		'ip_address',
		'method',
		'request_header',
		'request_body',
		'response',
		'status_code'
	];
	protected $hidden = ['updated_at'];
}
