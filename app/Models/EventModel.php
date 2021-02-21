<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventModel extends Model
{
	protected $table = 'xin_events';
	public $primarykey = 'event_id';
	
	public $timestamps = true;
	protected $fillable = [
		'event_id',
		'company_id',
		'event_type_id',
		'event_title',
		'event_date',
		'event_time',
		'event_note',
		'event_charge',
		'event_banner',
		'event_longitude',
		'event_latitude',
		'event_place',
		'event_speaker',
		'event_requirement',
		'event_additional_information',
		'event_prize',
		'event_terms_conditions',
		'event_label_terms_conditions'
	];
	protected $hidden = ['created_at','updated_at','event_terms_conditions','event_label_terms_conditions'];
	
	public function participants() {
		return $this->hasMany('App\Models\EventParticipantModel', 'event_id','event_id');
	}

	public function eventType() {
		return $this->hasMany('App\Models\Dashboard\EventTypeModel', 'event_type_id','event_type_id');
	}
	public function eventSchedules() {
		return $this->hasMany('App\Models\EventScheduleModel', 'event_id','event_id');
	}
}
