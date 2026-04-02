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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'media_category_id' => ['required', 'exists:media_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:image,video,document'],
            'file' => [$isUpdate ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,pdf,doc,docx', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
