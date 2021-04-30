<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteChoiceModel extends Model
{
    protected $table = 'vote_themes';
	public $primarykey = 'vote_themes_id';
	
	public $timestamps = true;
	protected $fillable = [
		'vote_choice_submit_id',
		'vote_choice_id',
		'vote_themes_id',
		'employee_id',
		'vote_status',
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
