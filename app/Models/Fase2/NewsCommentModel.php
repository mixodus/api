<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class NewsCommentModel extends Model
{
	protected $table = 'xin_news_comment';
	public $primarykey = 'comment_id';
	
	public $timestamps = true;
	protected $fillable = [
		'news_id',
		'user_id', 
		'comment',
		'desc',
		'attachment',
		'created_at'
	];
	protected $hidden = ['deleted_at','updated_at','status'];
	
	public function comment_replies() {
		return $this->hasMany('App\Models\Fase2\NewsCommentReplyModel', 'comment_id','comment_id');
	}
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'user_id','user_id');
  	}
}


