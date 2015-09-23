<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Subscription extends Model 
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
    protected $fillable = ['user_id', 'society_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function societies()
    {
        return $this->belongsTo('App\Society');
    }

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
