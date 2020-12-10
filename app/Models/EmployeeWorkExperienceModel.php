<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeWorkExperienceModel extends Model
{
	protected $table = 'xin_employee_work_experience';
	public $primarykey = 'work_experience_id';
	
	public $timestamps = true;
	protected $fillable = [
		'work_experience_id',
		'employee_id',
		'company_name',
		'start_period',
		'end_period',
		'post',
		'description'
	];
	protected $hidden = ['created_at','updated_at'];
	protected $dates = ['deleted_at'];
	
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'user_id','employee_id');
  	}
}
