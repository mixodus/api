<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    protected $table = 'kecamatan';
	public $primarykey = 'id_kec';
	protected $fillable = [
		'id_kab',
        'nama'
	];
	// protected $hidden = ['id_kab'];
}
