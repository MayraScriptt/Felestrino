<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link_url',
        'category',
        'file_path',
        'mime_type',
        'file_size',
        'is_video',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_video' => 'boolean',
        ];
    }

    public function audits(): HasMany
    {
        return $this->hasMany(MediaItemAudit::class);
    }
}
