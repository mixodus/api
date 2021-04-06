<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipantModel extends Model
{
    protected $table = 'xin_events_participant';
	public $primarykey = 'id';
	
	public $timestamps = true;
	
	protected $fillable = [
		'id',
		'event_id',
		'employee_id',
		'email',
		'fullname',
		'date_of_birth',
		'address',
		'country',
		'city',
		'gender',
		'status',
		'university',
		'major',
		'semester',
		'created_at',
		'modified_at',
		'updated_at',
		'cv_file',
		'link_drive',
	];
    protected $hidden = ['created_at','modified_at','updated_at'];
    
    public function event() {
		return $this->belongsTo('App\Models\EventModel', 'event_id','event_id');
	}
	public function schedules() {
		return $this->hasMany('App\Models\EventModel', 'event_id','event_id');
	}
	
	public function scheduleStatus() {
		return $this->hasMany('App\Models\EventParticipantStatusModel', 'employee_id','employee_id')->with('schedule');
	}
}
