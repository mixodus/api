<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteChoiceSubmitModel extends Model
{
    protected $table = 'vote_choice_submit';
	public $primarykey = 'vote_themes_id';
	
	public $timestamps = true;
	protected $fillable = [
		'id',
		'vote_themes_id',
		'vote_choice_id',
		'employee_id',
		'created_at',
		'updated_at',
	];
	protected $hidden = ['updated_at','deleted_at'];

	public function Choice(){
		return $this->belongsTo('App\Models\VoteChoiceModel', 'vote_choice_submit_id', 'vote_choice_id');
	}
	public function Theme(){
		return $this->belongsTo('App\Models\VoteThemeModel', 'vote_choice_submit_id', 'vote_themes_id');
	}
}
