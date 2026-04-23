<?php

namespace App\Http\Controllers;

use App\Models\HomeCard;
use App\Models\HomeCarouselItem;
use App\Models\MediaItem;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    public function home(): View
    {
        $data = Cache::remember(SiteCache::key('home'), now()->addMinutes(30), function () {
            $homePage = Page::query()
                ->where('slug', 'home')
                ->where('is_published', true)
                ->with(['sections' => fn ($query) => $query->where('is_published', true)])
                ->first();

            return [
                'settings' => $this->settings(),
                'homePage' => $homePage,
                'services' => Service::query()->where('is_published', true)->orderBy('display_order')->get(),
                'homeCarousel' => HomeCarouselItem::query()->where('is_active', true)->orderBy('display_order')->orderBy('id')->get(),
                'homeCards' => HomeCard::query()->where('is_active', true)->orderBy('display_order')->orderBy('id')->get(),
                'gallery' => MediaItem::query()
                    ->where('is_video', false)
                    ->orderBy('display_order')
                    ->limit(8)
                    ->get(),
            ];
        });

        $seo = [
            'title' => $data['settings']['seo_title'] ?? 'Felestrino Solucoes',
            'description' => $data['settings']['seo_description'] ?? 'Solucoes inteligentes para irrigacao, pivos e monitoramento hidrico.',
        ];

        return view('welcome', $data + ['seo' => $seo]);
    }

    public function page(string $slug): View
    {
        $data = Cache::remember(SiteCache::key("page:{$slug}"), now()->addMinutes(30), function () use ($slug) {
            $page = Page::query()
                ->where('slug', $slug)
                ->where('is_published', true)
                ->with(['sections' => fn ($query) => $query->where('is_published', true)])
                ->firstOrFail();

            return [
                'settings' => $this->settings(),
                'page' => $page,
            ];
        });

        $seo = [
            'title' => $data['page']->meta_title ?: $data['page']->title,
            'description' => $data['page']->meta_description ?: ($data['settings']['seo_description'] ?? ''),
        ];

        return view('site.page', $data + ['seo' => $seo]);
    }

    private function settings(): array
    {
        return [
            'company_name' => Setting::getValue('company_name', 'Felestrino Solucoes'),
            'tagline' => Setting::getValue('tagline', 'Tecnologia para agua e irrigacao'),
            'phone' => Setting::getValue('phone'),
            'email' => Setting::getValue('email'),
            'address' => Setting::getValue('address'),
            'about' => Setting::getValue('about'),
            'seo_title' => Setting::getValue('seo_title'),
            'seo_description' => Setting::getValue('seo_description'),
        ];
    }
}
