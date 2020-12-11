<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
    //
      protected $table = 'xin_banners';
      public $primarykey = 'banners_id';
      
      public $timestamps = true;
      protected $fillable = [
          'banners_id',
          'banners_type_id',
          'banners_detail_id',
          'banners_photo'
      ];
      protected $hidden = ['created_at','updated_at'];
}
