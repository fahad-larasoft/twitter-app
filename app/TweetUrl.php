<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TweetUrl extends Model
{
    protected $fillable = [
        'url',
        'reach_date_time'
    ];

    protected $dates = ['reach_date_time'];

    public function retweetUsers(){
        return $this->hasMany(RetweetUser::class);
    }
}
