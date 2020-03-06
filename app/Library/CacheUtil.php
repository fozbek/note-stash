<?php

namespace App\Library;

class CacheUtil
{
    public static function getCacheKey($payload)
    {
        return md5(serialize($payload));
    }

    public static function isCacheEnabled()
    {
        return env('CACHE_ENABLED', false);
    }

    public static function getTtl()
    {
        return env('CACHE_TTL', 10);
    }
}
