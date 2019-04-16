<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wxvoice extends Model
{
    //
    protected $table = 'wxvoice';
    public $timestamps = false;
    public $primaryKey = 'v_id';
}
