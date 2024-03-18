<?php

namespace App\Utils;

class BaseUrl
{
    public static function get()
    {
        // Return the base URL of your application
        return config('app.url');
    }
}