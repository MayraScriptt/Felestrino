<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'content',
        'is_published',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('display_order');
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => str($value ?? '')->slug()->value(),
        );
    }
}
