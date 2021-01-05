<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class JobTypeListModel extends Model
{
	protected $table = 'xin_jobtypelist';
	public $primarykey = 'job_type_list_id';
	
	public $timestamps = true;
	protected $fillable = [
		'job_id',
		'type_id'
	];
	protected $hidden = ['created_at', 'deleted_at','updated_at'];
	
	public function job() {
		return $this->belongsTo('App\Models\JobsModel', 'job_id','job_id');
	}
	public function jobtype() {
		return $this->belongsTo('App\Models\Fase2\JobTypeModel', 'type_id','job_type_id');
	}
}
