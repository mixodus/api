<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRewardModel extends Model
{
	protected $table = 'xin_withdraw';
	public $primarykey = 'withdraw_id';
	
	public $timestamps = true;
	protected $fillable = [
		'withdraw_id',
		'user_id',
		'number_of_success',
		'total_number',
		'current_amount',
		'total_amount',
		'updated_at'
	];
	
	protected $hidden = ['updated_at'];
}
