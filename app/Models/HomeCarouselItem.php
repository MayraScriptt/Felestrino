<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCarouselItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'link_url',
        'button_text',
        'button_url',
        'image_path',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
