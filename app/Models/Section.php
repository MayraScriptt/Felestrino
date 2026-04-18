<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'title',
        'section_key',
        'subtitle',
        'content',
        'image_path',
        'is_published',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
