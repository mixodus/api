<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwardModel extends Model
{
	protected $table = 'xin_awards';
	public $primarykey = 'award_id';
	
	public $timestamps = true;
	protected $fillable = [
		'award_id',
		'company_id',
		'employee_id',
		'award_type_id',
		'gift_item',
		'cash_price',
		'award_photo',
		'award_month_year',
		'award_information',
		'description'
	];
	protected $hidden = ['created_at','updated_at'];
}
