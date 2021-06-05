<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeQualificationModel extends Model
{
	protected $table = 'xin_employee_qualification';
	public $primarykey = 'qualification_id';
	
	public $timestamps = true;
	protected $fillable = [
		'qualification_id',
		'employee_id',
		'name',
		'education_level_id',
		'skill_id',
		'description',
		'gpa',
		'field_of_study',
		'start_period', 
		'end_period'
	];
	protected $hidden = ['created_at','updated_at'];
}
