<?php

namespace App\Models\Fase2;

use Illuminate\Database\Eloquent\Model;

class NewsCommentReplyModel extends Model
{
	protected $table = 'xin_news_comment_reply';
	public $primarykey = 'reply_id';
	
	public $timestamps = true;
	protected $fillable = [
		'comment_id',
		'comment_by', 
		'reply_by',
		'comment',
		'desc',
		'attachment',
		'created_at'
	];
	protected $hidden = ['modified_at','updated_at','deleted_at'];

	public function comment() {
		return $this->belongsTo('App\Models\Fase2\NewsCommentModel', 'comment_id','comment_id');
	}
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'reply_by','user_id');
  	}
}
