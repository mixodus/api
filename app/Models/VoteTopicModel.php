<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteTopicModel extends Model
{
    protected $table = 'vote_topics';
	public $primarykey = 'topic_id';
	
	
	public $timestamps = true;
	protected $fillable = [
		'topic_id',
		'name',
		'title',
		'banner', 
		'created_at',
		'updated_at',
	];
    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function Choice(){
		return $this->hasMany('App\Models\VoteChoiceModel', 'id', 'vote_topic_id');
	}
    public function ChoiceSubmit(){
		return $this->hasMany('App\Models\VoteChoiceSubmitModel', 'vote_choice_submit_id', 'vote_topic_id');
	}
}
