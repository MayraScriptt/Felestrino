<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'detail_enabled',
        'detail_title',
        'detail_subtitle',
        'detail_body',
        'detail_image_path',
        'detail_image_paths',
        'detail_image_caption',
        'detail_button_text',
        'icon',
        'link_url',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'detail_enabled' => 'boolean',
            'detail_image_paths' => 'array',
        ];
    }
}
