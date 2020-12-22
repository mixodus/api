<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    protected $table = 'xin_countries';
	public $primarykey = 'country_id';
	protected $fillable = [
		'country_name',
		'country_flag'
	];
	protected $hidden = ['country_flag'];
	
}
