<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Quote extends Model 
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quotes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'subtitle'];
}
