<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobsModel extends Model
{
	protected $table = 'xin_jobs';
	public $primarykey = 'job_id';
	
	protected $fillable = [
		'job_id' ,
		'company_id' ,
		'job_title',
		'designation_id',
		'job_type',
		'is_featured' ,
		'job_vacancy',
		'gender',
		'minimum_experience',
		'date_of_closing',
		'short_description',
		'long_description',
		'status' ,
		'province',
		'country',
		'created_at'
	]; 
	protected $hidden = ['updated_at','deleted_at'];
	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at'
	];
	public function company() {
		return $this->belongsTo('App\Models\CompanyModel', 'company_id','company_id');
	}

	public function applications() {
		return $this->belongsTo('App\Models\JobsApplicationModel', 'job_id','job_id');
  	}
}
