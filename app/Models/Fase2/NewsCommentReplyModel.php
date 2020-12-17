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
		'attachment'
	];
	protected $hidden = ['created_at', 'modified_at','updated_at'];

	public function comment() {
		return $this->belongsTo('App\Models\Fase2\NewsCommentModel', 'comment_id','comment_id');
	}
	
	public function user() {
		return $this->belongsTo('App\Models\UserModels', 'user_id','reply_by');
  	}
}
