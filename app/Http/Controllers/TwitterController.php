<?php

namespace App\Http\Controllers;

use App\TweetUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Thujohn\Twitter\Facades\Twitter;

class TwitterController extends Controller
{
    public function getDetails(Request $request)
    {
        $this->validate($request, ['url' => 'required|url']);

        // Break the URL into parts
        $url = explode('/', $request->url);

        // Get Tweet ID from last part of URL
        $tweet_id = end($url);

        // Check if the URL is already recorded and under 2 hours?
        // YES, then return old one
        $tweetUrl = TweetUrl::whereUrl($request->url)->first();
        if ($tweetUrl) {
            if ($tweetUrl->reach_date_time->addHours(2) > Carbon::now()) {
                return $tweetUrl->load('retweetUsers');
            }
        }
        else
        {
            // Create new Tweet Url
            $tweetUrl = TweetUrl::create(['url' => $request->url, 'reach_date_time' => Carbon::now()]);
        }

        try
        {
            // Get Re-tweeted Users
            $data = Twitter::getRts($tweet_id);

            // Get Re-tweeting users
            $retweet_users = collect($data)->pluck('user');

            // Get Followers of Re-tweeting users
            foreach ($retweet_users as $retweet_user)
            {
                $user_id = $retweet_user->id;

                $followers = Twitter::getFollowers(['user_id' => $user_id]);

                $tweetUrl->retweetUsers()->delete();

                $tweetUrl->retweetUsers()->create([
                    'user_name' => $retweet_user->name,
                    'profile_image_url' => $retweet_user->profile_image_url,
                    'followers_count' => count($followers)
                ]);
            }
        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
            $tweetUrl->delete();
            return response()->json(['errors' => [[$e->getMessage()]]], 422);
        }


        return $tweetUrl->load('retweetUsers');
    }
}
