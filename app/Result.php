<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
