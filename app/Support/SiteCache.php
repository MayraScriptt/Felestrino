<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class SiteCache
{
    public const VERSION_KEY = 'site-cache-version';

    public static function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    public static function key(string $suffix): string
    {
        return sprintf('site:v%s:%s', self::version(), $suffix);
    }

    public static function bump(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
    }
}
