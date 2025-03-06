<?php

namespace App\Helpers;

class Bucket
{
    public static function all()
    {
        return config('buckets.values');
    }
}
