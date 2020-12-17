<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
	protected $table = 'xin_level';
	public $primarykey = 'level_id';
	
	public $timestamps = true;
	protected $fillable = [
		'level_id',
		'level_name',
		'level_code',
		'level_icon',
		'level_min_point',
		'level_max_point',
		'modified_at'
	];
	protected $hidden = ['created_at', 'modified_at','updated_at'];
}
