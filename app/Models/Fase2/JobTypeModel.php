<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class JobTypeModel extends Model
{
	protected $table = 'xin_job_type';
	public $primarykey = 'job_type_id';
	
	public $timestamps = true;
	protected $fillable = [
		'job_type_id',
		'company_id', 
		'type',
		'type_url'
	];
	protected $hidden = ['created_at'];

	public function jobtypeList() {
		return $this->hasMany('App\Models\Fase2\JobTypeListModel', 'type_id','job_type_id');
	}

}
