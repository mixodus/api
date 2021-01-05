<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBankModel extends Model
{
    protected $table = 'xin_employee_bank_account';
	public $primarykey = 'account_list_id';
    public $timestamps = true;
    
	protected $fillable = [
		'employee_id',
		'account_name',
		'account_number',
		'total_number',
		'is_primary',
		'bank_id',
		'created_at',
		'updated_at'
	];
	
	protected $hidden = ['updated_at','deleted_at'];
	protected $dates = ['deleted_at'];
}
