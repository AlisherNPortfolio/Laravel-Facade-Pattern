<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TwitterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'twitter-poster';
    }
}
