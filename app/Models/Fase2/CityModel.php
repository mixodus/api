<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    protected $table = 'kabupaten';
	public $primarykey = 'id_kab';
	protected $fillable = [
		'id_prov',
        'nama',
        'id_jenis'
	];
	protected $hidden = ['id_jenis'];
}
