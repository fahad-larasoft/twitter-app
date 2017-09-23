<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetweetUser extends Model
{
    protected $fillable = [
        'user_name',
        'profile_image_url',
        'followers_count'
    ];

    public function tweetUrl()
    {
        return $this->belongsTo(TweetUrl::class);
    }
}
