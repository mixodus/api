<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
	protected $table = 'xin_companies';
	public $primarykey = 'company_id';
	
	public $timestamps = true;
	
	protected $fillable = [
		'company_id',
		'type_id',
		'name',
		'trading_name',
		'username',
		'password',
		'registration_no',
		'government_tax',
		'email',
		'logo',
		'contact_number',
		'website_url',
		'address_1',
		'address_2',
		'city',
		'state',
		'zipcode',
		'country',
		'is_active',
		'added_by'
	]; 
	protected $hidden = ['created_at','updated_at','deleted_at'];
	
	public function jobs() {
		return $this->hasMany('App\Models\JobsModel', 'company_id','company_id');
  	}
}
