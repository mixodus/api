<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProjectExperienceModel extends Model
{
	protected $table = 'xin_employee_project_experiences';
	public $primarykey = 'id';
	
	public $timestamps = true;
	protected $fillable = [
		'id',
		'employee_id',
		'work_experience_id',
		'project_name',
		'position',
		'jobdesc',
		'start_period',
		'end_period',
		'tools'
	];
	protected $hidden = ['created_at','updated_at'];
}
