<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeParticipants extends Model
{
	protected $table = 'xin_challenge_participant';
	public $primarykey = 'id';
	
	public $timestamps = true;
	protected $fillable = [
		'id',
		'challenge_id',
		'list_quiz_id',
		'list_quiz_answer',
		'employee_id',
		'total_point',
		'total_current_point',
		'total_current_task',
		'total_task',
		'is_achieve'
	];
	protected $hidden = ['created_at','modified_at','updated_at'];

	public function challenge() {
		return $this->belongsTo('App\Models\ChallengeModel', 'challenge_id','challenge_id');
  	}
}
