<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendModel extends Model
{
	protected $table = 'xin_friendship';
	public $primarykey = 'request_id';
	public $timestamps = true;
	protected $fillable = [
		'request_id',
		'uid1',
		'uid2'
	];
}
