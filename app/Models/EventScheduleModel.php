<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventScheduleModel extends Model
{
	use SoftDeletes;

	protected $table = 'xin_event_schedule';
	public $primarykey = 'schedule_id';
	
	public $timestamps = true;
	protected $fillable = [
		'event_id',
		'schedule_start',
		'schedule_end',
		'icon',
		'name',
		'desc',
		'link',
		'additional_information'
	];
	protected $hidden = ['created_at','updated_at','deleted_at'];
	
	public function participants() {
		return $this->belongsTo('App\Models\EventModel', 'event_id','event_id');
	}

	public function eventType() {
		return $this->hasMany('App\Models\Dashboard\EventTypeModel', 'event_type_id','event_type_id');
	}
	public function scheduleStatus() {
		return $this->belongsTo('App\Models\EventParticipantStatusModel', 'schedule_id','schedule_id');
	}
	public function participant() {
		return $this->belongsTo('App\Models\EventParticipantModel', 'event_id','event_id');
	}
}
