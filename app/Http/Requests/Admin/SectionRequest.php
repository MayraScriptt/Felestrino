<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_id' => ['required', 'exists:pages,id'],
            'title' => ['required', 'string', 'max:150'],
            'section_key' => ['nullable', 'string', 'max:100'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
