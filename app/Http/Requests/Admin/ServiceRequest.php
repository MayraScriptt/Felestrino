<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', Rule::unique('services', 'slug')->ignore($serviceId)],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'is_highlight' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
