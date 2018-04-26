<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The admins that belong to the day off
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admin()
    {
        return $this->belongsToMany('App\Admin')->withTimestamps();
    }
}
