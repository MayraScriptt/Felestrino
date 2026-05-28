<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'banner_path',
        'banner_subtitle',
        'banner_description',
        'banner_position_x',
        'banner_position_y',
        'media_positions',
    ];

    protected function casts(): array
    {
        return [
            'media_positions' => 'array',
        ];
    }
}
