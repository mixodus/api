<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFriendshipModel extends Model
{
	protected $table = 'xin_friendship';
	public $primarykey = 'request_id';
	
	protected $fillable = [
		'request_id',
		'uid1',
		'uid2'
	];
}
