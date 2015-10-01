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
    protected $table = 'subscriptions';

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

    /**
     * Get the society from a subscription
     */
    public function societies()
    {
        return $this->belongsTo('App\Society');
    }

    /**
     * Get the user from a subscription
     */
    public function user(){
    	return $this->belongsTo('App\User');
    }
}
