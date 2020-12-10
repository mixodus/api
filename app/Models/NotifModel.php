<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifModel extends Model
{
    protected $table = 'xin_notif';
    public $primarykey = 'notif_id';
    
    public $timestamps = true;
    protected $fillable = [
        'notif_type_id',
        'notif_detail_id',
        'title',
        'user_id',
        'description',
        'long_description',
        'is_new',
        'modified_at'
    ];
	// protected $hidden = ['created_at', 'modified_at','updated_at'];
}
