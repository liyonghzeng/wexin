<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Order extends Model
{
    //
    protected $table = 'order';
    public $timestamps = false;
    public $primaryKey = 'o_id';
    public static function dj(){
        $shuffle=time().Str::random(16)."lyz".rand(11111,99999);
        $ss = substr(md5($shuffle),2,17);
        return $ss;
    }
}
