<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class UserTrxMailChangeModel extends Model
{
    protected $table = 'xin_trx_change_email';
	public $primarykey = 'trx_id';
	protected $fillable = [
		'trx_id',
        'user_id',
        'email',
        'is_mail_verified'
	];
	protected $hidden = ['created_at','updated_at'];
}
