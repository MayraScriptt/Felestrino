<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'image_path',
        'is_highlight',
        'is_published',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_highlight' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => str($value ?? '')->slug()->value(),
        );
    }
}
