<?php

namespace App\Http\Controllers;

use App\Facades\TwitterFacade;
use DG\Twitter\Twitter;

class PostController extends Controller
{
    public function twit()
    {
        // $twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        // $twitter->send('Hello, friend!');
        TwitterFacade::get()->send('Hello, friend!');
    }
}
