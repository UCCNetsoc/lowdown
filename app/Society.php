<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Society extends Model 
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'societies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'facebook_ref'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the events for a society
     */
    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
