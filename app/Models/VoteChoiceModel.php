<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteChoiceModel extends Model
{
    protected $table = 'vote_choice';
	public $primarykey = 'vote_themes_id';
	
	public $timestamps = true;
	protected $fillable = [
		'id',
		'vote_themes_id',
		'name',
		'icon',
		'created_at',
		'updated_at',
	];
	protected $hidden = ['vote_themes_id','created_at','updated_at','deleted_at'];

	public function Theme(){
		return $this->belongsTo('App\Models\VoteThemeModel', 'vote_themes_id', 'id');
	}public function ChoiceSubmit(){
		return $this->hasMany('App\Models\VoteChoiceSubmitModel', 'vote_choice_submit_id', 'vote_choice_id');
	}
}
