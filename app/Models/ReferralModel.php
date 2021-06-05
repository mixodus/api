<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dashboard\AdminModel;
//use App\Models\UserModel;

class ReferralModel extends Model
{
	protected $table = 'xin_referral';
	public $primarykey = 'referral_id';
	
	public $timestamps = true;
	protected $fillable = [
		'referral_id',
		'source',
		'referral_name',
		'referral_email',
		'referral_contact_no',
		'referral_status',
		'file',
		'fee',
		'job_position',
		'referral_employee_id',
		'created_at',
		'modified_at',
		'withdraw_reward',
		'added_to_transaction_point',
		'updated_at'
	];
	protected $hidden = ['created_at','updated_at','modified_at'];

	public function UserModel(){
		return $this->belongsTo('App/Models/UserModels', 'referral_employee_id', 'user_id');
	}
	public function AdminModel(){
		return $this->belongsTo(AdminModel::class, 'referral_employee_id', 'user_id');
	}

}
