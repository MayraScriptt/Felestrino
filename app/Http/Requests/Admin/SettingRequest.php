<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'hero_title' => ['nullable', 'string', 'max:160'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_image_url' => ['nullable', 'string', 'max:2048'],
            'seo_title' => ['nullable', 'string', 'max:160'],
            'seo_description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
