<?php

namespace App\Models\dashboard;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'xin_dashboard_log';
    public $primarykey = 'id';
    
    public $timestamps = true;
	protected $fillable = [
		'server_type',
        'type', 
        'module',
        'name',
        'uri',
		'user_id',
		'ip_address',
		'method', 
		'request_header', 
		'request_body',
        'response',
        'status_code',
	];
}
