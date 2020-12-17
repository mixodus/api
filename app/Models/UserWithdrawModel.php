<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWithdrawModel extends Model
{
    protected $table = 'xin_withdraw';
	public $primarykey = 'withdraw_id';
    public $timestamps = true;
      
	protected $fillable = [
		'user_id',
		'number_of_success',
		'total_number',
		'current_amount',
		'total_amount',
		'created_at',
		'updated_at'
	];
	
	protected $hidden = ['updated_at','deleted_at'];
	protected $dates = ['deleted_at'];
}
