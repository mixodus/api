<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteChoiceModel extends Model
{
    protected $table = 'vote_themes';
	public $primarykey = 'vote_themes_id';
	
	public $timestamps = true;
	protected $fillable = [
		'vote_choice_id',
		'vote_themes_id',
		'created_at',
		'updated_at',
	];
	protected $hidden = ['updated_at','deleted_at'];

	public function Theme(){
		return $this->belongsTo('App\Models\VoteThemeModel', 'vote_choice_id', 'vote_themes_id');
	}public function ChoiceSubmit(){
		return $this->hasMany('App\Models\VoteChoiceSubmitModel', 'vote_choice_submit_id', 'vote_choice_id');
	}
}
