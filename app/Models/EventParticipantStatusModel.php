<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipantStatusModel extends Model
{
    protected $table = 'xin_event_participant_status';
    public $primarykey = 'status_id';
      
    public $timestamps = true;
    protected $fillable = [
        'employee_id',
        'schedule_id',
        'status'
    ];
    protected $hidden = ['created_at','updated_at'];

    public function schedules() {
		return $this->hasMany('App\Models\EventScheduleModel', 'schedule_id','schedule_id');
	}
}
