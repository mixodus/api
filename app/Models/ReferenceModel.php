<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceModel extends Model
{
    protected $table = 'xin_reference';
	public $primarykey = 'id';
    protected $fillable = [
        'id',
        'name',
        'category'
    ];
}
