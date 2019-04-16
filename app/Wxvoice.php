<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wxvoice extends Model
{
    //
    protected $table = 'WxVoice';
    public $timestamps = false;
    public $primaryKey = 'v_id';
}
