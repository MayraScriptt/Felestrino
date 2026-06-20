<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Cache;
>>>>>>> dev2

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

<<<<<<< HEAD
    public static function value(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
=======
    public static function getValue(string $key, ?string $default = null): ?string
    {
        return Cache::rememberForever("setting:{$key}", fn () => static::query()
            ->where('key', $key)
            ->value('value') ?? $default);
>>>>>>> dev2
    }
}
