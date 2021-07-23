<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConnectionModel extends Model
{
    protected $table = 'user_connection';
	public $primarykey = 'id';
	
	public $timestamps = true;
	
	protected $fillable = [
		'user_id',
        'user_connection_id',
        'status',
        'is_following',
        
	]; 
    protected $hidden = ['created_at','updated_at','deleted_at'];
}
