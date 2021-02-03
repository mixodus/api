<?php

namespace App\Models\dashboard;

use Illuminate\Database\Eloquent\Model;

class EventTypeModel extends Model
{
    protected $table = 'xin_events_type';
	public $primarykey = 'event_type_id';
	
	public $timestamps = true;
	protected $fillable = [
		'event_type_id',
		'event_type_name',
		'event_type_description'
	];
	protected $hidden = ['created_at','modified_at'];
}
