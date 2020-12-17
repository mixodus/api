<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWithdrawHistoryModel extends Model
{
    protected $table = 'xin_withdraw_history';
	public $primarykey = 'withdraw_history_id';
    public $timestamps = true;
      
	protected $fillable = [
		'money_withdrawal',
		'transaction_date',
		'transaction_status',
		'transaction_note',
		'account_list_id',
		'created_at',
		'updated_at'
	];
	
	protected $hidden = ['updated_at','deleted_at'];
	protected $dates = ['deleted_at'];
}
