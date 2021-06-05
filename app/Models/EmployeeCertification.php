<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCertification extends Model
{
	protected $table = 'xin_employee_certification';
	public $primarykey = 'certification_id';
	
	public $timestamps = true;
	
	protected $fillable = [
		'certification_id',
		'employee_id',
		'certification_date',
		'title',
		'description',
		'certification_file'
	];
	protected $hidden = ['created_at','updated_at'];
	
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'user_id','employee_id');
  	}
}
