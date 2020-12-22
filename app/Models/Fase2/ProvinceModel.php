<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class ProvinceModel extends Model
{
    protected $table = 'provinsi';
	public $primarykey = 'id_prov';
	protected $fillable = [
		'nama'
	];
}
