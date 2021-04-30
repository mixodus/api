<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteThemeModel extends Model
{
    protected $table = 'vote_themes';
	public $primarykey = 'vote_themes_id';
	
	public $timestamps = true;
	protected $fillable = [
		'vote_themes_id',
		'theme',
		'banner',
		'created_at',
		'updated_at',
	];
    protected $hidden = ['updated_at','deleted_at'];

    public function Choice(){
		return $this->hasMany('App\Models\VoteChoiceModel', 'vote_choice_id', 'vote_themes_id');
	}
    public function ChoiceSubmit(){
		return $this->hasMany('App\Models\VoteChoiceSubmitModel', 'vote_choice_submit_id', 'vote_themes_id');
	}
}
