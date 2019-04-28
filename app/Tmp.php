<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tmp extends Model
{
    protected $table = 'tmp';
    public $timestamps = false;
    public $primaryKey = 'tmp_id';
}