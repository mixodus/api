<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerNewsModel extends Model
{
    protected $table = 'xin_banners_news';
	public $primarykey = 'news_id';
	
	public $timestamps = true;
	protected $fillable = [
		'news_detail_id',
		'new_photo'
	];
	// protected $hidden = ['created_at','updated_at'];
}
