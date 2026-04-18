<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MediaItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:80'],
            'file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,mp4,mov,avi,webm', 'max:20480'],
            'is_video' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
