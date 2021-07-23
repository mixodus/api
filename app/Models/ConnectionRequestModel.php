<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectionRequestModel extends Model
{
    protected $table = 'connection_request';
	public $primarykey = 'id';
	
	public $timestamps = true;
	
	protected $fillable = [
		'source_id',
        'target_id',
        'status',
        
	]; 
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
