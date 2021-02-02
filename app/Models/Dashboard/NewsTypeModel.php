<?php

namespace App\Models\dashboard;

use Illuminate\Database\Eloquent\Model;

class NewsTypeModel extends Model
{
    protected $table = 'xin_news_type';
	public $primarykey = 'news_type_id';
	
	public $timestamps = true;
	protected $fillable = [
		'news_type_name',
		'news_type_description', 
		'news_colour', 
		'created_at',
		'modified_at',
	];
	protected $hidden = ['created_at', 'modified_at','updated_at'];
}
