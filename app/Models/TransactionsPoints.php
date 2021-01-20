<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsPoints extends Model
{
    protected $table = 'xin_transaction_point';
    public $primarykey = 'id';
    
    public $timestamps = true;
    protected $fillable = [
        'activity_point_code',
        'point',
        'employee_id',
        'challenge_id',
        'status'
    ];
	protected $hidden = ['modified_at','updated_at'];
}
