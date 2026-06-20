<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
=======
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> dev2
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
<<<<<<< HEAD
        'hero_title',
        'hero_subtitle',
        'content',
        'meta_title',
        'meta_description',
        'is_published',
        'sort_order',
=======
        'meta_title',
        'meta_description',
        'content',
        'is_published',
        'display_order',
>>>>>>> dev2
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

<<<<<<< HEAD
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
=======
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => str($value ?? '')->slug()->value(),
        );
>>>>>>> dev2
    }
}
