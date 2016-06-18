<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    protected $fillable = array('user_id', 'first_name', 'last_name', 'created_at', 'updated_at');
}
