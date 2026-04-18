<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', Rule::unique('pages', 'slug')->ignore($pageId)],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
