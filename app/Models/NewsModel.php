<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
	protected $table = 'xin_news';
	public $primarykey = 'news_id';
	
	public $timestamps = true;
	protected $fillable = [
		'news_id',
		'news_title', 
		'news_type_id',
		'news_details',
		'news_url', 
		'news_photo', 
		'created_at',
		'modified_at',
	];
	protected $hidden = ['modified_at','updated_at'];
	
	public function comments() {
		return $this->hasMany('App\Models\Fase2\NewsCommentModel', 'news_id','news_id')->with(['comment_replies','user'=>function($query){
			$query->select('user_id','fullname');
		}]);
  	}
}
