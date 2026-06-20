<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
>>>>>>> dev2

class MediaItem extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        'media_category_id',
        'title',
        'type',
        'file_path',
        'alt_text',
        'size',
        'is_active',
        'sort_order',
=======
        'title',
        'link_url',
        'category',
        'file_path',
        'mime_type',
        'file_size',
        'is_video',
        'display_order',
>>>>>>> dev2
    ];

    protected function casts(): array
    {
        return [
<<<<<<< HEAD
            'is_active' => 'boolean',
        ];
    }

    public function mediaCategory(): BelongsTo
    {
        return $this->belongsTo(MediaCategory::class);
=======
            'is_video' => 'boolean',
        ];
    }

    public function audits(): HasMany
    {
        return $this->hasMany(MediaItemAudit::class);
>>>>>>> dev2
    }
}
