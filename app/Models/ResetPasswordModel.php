<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPasswordModel extends Model
{
    protected $table = 'xin_reset_password';
    public $primarykey = 'id';
    
    public $timestamps = true;
    protected $fillable = [
        'code',
        'expired_at',
        'created_at',
        'email',
        'is_used'
    ];
	protected $hidden = ['created_at'];
}
