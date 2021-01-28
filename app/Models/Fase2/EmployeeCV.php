<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class EmployeeCV extends Model
{
    protected $table = 'xin_employee_cv';
	public $primarykey = 'employee_cv_id';
	public $timestamps = true;
	protected $fillable = [
		'employee_id',
		'file', 
		'desc',
    ];
    
	protected $hidden = ['deleted_at','updated_at','created_at'];
	
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'user_id','employee_id');
  	}
}
