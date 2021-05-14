<?php

namespace App\Models\dashboard;

use Illuminate\Database\Eloquent\Model;
use App\Models\RolesModel;
use App\Models\ReferralModel;

class AdminModel extends Model
{
    protected $table = 'xin_users';
    public $primarykey = 'user_id';
    public $timestamps = false;
	protected $fillable = [
			'user_id',
			'user_role',
			'first_name',
			'last_name',
			'company_name',
			'company_logo',
			'user_type',
			'role_id',
			'email',
			'username',
			'password',
			'profile_photo',
			'profile_background',
			'contact_number',
			'gender',
			'address_1',
			'address_2',
			'city',
			'state',
			'zipcode',
            'country',
            'is_active'
    ];
    
    public function role()
    {
        return $this->hasOne(RolesModel::class, 'user_id', 'role_id')
            ->select('access_role');
	}

	//referral
	public function referral(){
		return $this->hasMany(ReferralModel::class, 'referral_employee_id', 'user_id');
	}
}
