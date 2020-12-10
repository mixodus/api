<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobsApplicationModel extends Model
{
	protected $table = 'xin_job_applications';
	public $primarykey = 'application_id';

	public $timestamps = true;
	protected $fillable = [
				'application_id',
				'job_id',
				'user_id',
				'email',
				'contact_no',
				'message',
				'job_resume',
				'application_status',
				'application_remarks',
				'created_at'
		]; 
		protected $hidden = ['created_at','updated_at','deleted_at'];
		
		public function job() {
		return $this->belongsTo('App\Models\JobsModel', 'job_id','job_id');
		}

		public function user() {
		return $this->belongsTo('App\Models\UserModel', 'user_id','user_id');
		}  

}
