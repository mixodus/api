<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
	protected $table = 'xin_countries';
	public $primarykey = 'country_id';

	public $timestamps = true;
	protected $fillable = [
		'country_id',
		'country_code',
		'country_name',
		'country_flag'
	];
}
