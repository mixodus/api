<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeQuiz extends Model
{
	protected $table = 'xin_challenge_quiz';
	public $primarykey = 'id';
	
	public $timestamps = true;
	protected $fillable = [
		'id',
		'challenge_id',
		'question',
		'a',
		'b',
		'c',
		'answer',
		'point'
	];
	public function challenge() {
		return $this->belongsTo('App\Models\ChallengeModel', 'challenge_id','challenge_id');
  	}
}
