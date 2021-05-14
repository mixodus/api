<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeModel extends Model
{
	protected $table = 'xin_challenge';
	public $primarykey = 'challenge_id';
	
	public $timestamps = true;
	protected $fillable = [
		'challenge_id',
		'challenge_title',
		'challenge_type_id',
		'challenge_point',
		'challenge_point_every_task',
		'challenge_expired_date',
		'challenge_description',
		'challenge_long_desciption',
		'challenge_photo',
		'challenge_total_task',
		'challenge_icon_trophy',
		'challenge_title_trophy'
	];
	protected $hidden = ['created_at','updated_at,modified_at'];

	public function top_participant() {
		return $this->hasMany('App\Models\ChallengeParticipants', 'challenge_id','challenge_id');
	}
	//untuk challenge detail
	public function me() {
		return $this->belongsTo('App\Models\ChallengeParticipants', 'challenge_id','challenge_id');
	}

	public function quiz() {
		return $this->hasMany('App\Models\ChallengeQuiz', 'challenge_id','challenge_id');
  	}
	
}
