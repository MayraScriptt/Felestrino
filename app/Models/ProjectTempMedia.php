<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTempMedia extends Model
{
    use HasFactory;

    protected $table = 'project_temp_media';

    protected $fillable = [
        'draft_token',
        'type',
        'image_path',
        'youtube_id',
        'youtube_url',
        'description',
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

