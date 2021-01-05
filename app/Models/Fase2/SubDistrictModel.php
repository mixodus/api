<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class SubDistrictModel extends Model
{
    protected $table = 'kelurahan';
	public $primarykey = 'id_kel';
	protected $fillable = [
		'id_kec',
        'nama',
        'id_jenis'
	];
	protected $hidden = ['id_jenis'];
}
