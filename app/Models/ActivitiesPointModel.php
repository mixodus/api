<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesPointModel extends Model
{
    protected $table = 'xin_activity_point';
	public $primarykey = 'activity_point_id';
    public $timestamps = true;
    protected $fillable = [
        'activity_point_name',
        'activity_point_code',
        'activity_point_point'
    ];
	protected $hidden = ['created_at', 'modified_at'];
}
