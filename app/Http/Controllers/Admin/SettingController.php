<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function edit(): View
    {
        $keys = $this->keys();
        $settings = Setting::query()->whereIn('key', $keys)->pluck('value', 'key');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        foreach ($this->keys() as $key) {
            $value = $request->input($key);
            if (is_string($value) && trim($value) === '') {
                $value = null;
            }

            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            Cache::forget("setting:{$key}");
        }

        SiteCache::bump();

        return redirect()->route('admin.settings.edit')->with('status', 'Configuracoes atualizadas com sucesso.');
    }

    private function keys(): array
    {
        return [
            'company_name',
            'tagline',
            'phone',
            'email',
            'address',
            'about',
            'hero_title',
            'hero_subtitle',
            'hero_image_url',
            'seo_title',
            'seo_description',
        ];
    }
}
