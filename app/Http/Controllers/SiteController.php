<?php

namespace App\Http\Controllers;

use App\Models\MediaCategory;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $page = Cache::remember('site.page.home', now()->addMinutes(30), function () {
            return Page::query()
                ->with(['sections' => fn ($query) => $query->where('is_active', true)])
                ->where('slug', 'home')
                ->where('is_published', true)
                ->first();
        });

        $services = Cache::remember('site.services', now()->addMinutes(30), function () {
            return Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        $gallery = Cache::remember('site.gallery', now()->addMinutes(30), function () {
            return MediaCategory::query()
                ->with(['mediaItems' => fn ($query) => $query->where('is_active', true)])
                ->orderBy('sort_order')
                ->get();
        });

        return view('site.home', [
            'page' => $page,
            'services' => $services,
            'gallery' => $gallery,
            'metaTitle' => $page?->meta_title ?: Setting::value('seo_default_title', 'Felestrino Soluções'),
            'metaDescription' => $page?->meta_description ?: Setting::value('seo_default_description', ''),
        ]);
    }

    public function about(): View
    {
        $page = Cache::remember('site.page.about', now()->addMinutes(30), function () {
            return Page::query()
                ->with(['sections' => fn ($query) => $query->where('is_active', true)])
                ->where('slug', 'sobre')
                ->where('is_published', true)
                ->first();
        });

        return view('site.about', [
            'page' => $page,
            'metaTitle' => $page?->meta_title ?: Setting::value('seo_default_title', 'Sobre'),
            'metaDescription' => $page?->meta_description ?: Setting::value('seo_default_description', ''),
        ]);
    }

    public function services(): View
    {
        $services = Cache::remember('site.services', now()->addMinutes(30), function () {
            return Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('site.services', [
            'services' => $services,
            'metaTitle' => 'Serviços | '.Setting::value('seo_default_title', 'Felestrino Soluções'),
            'metaDescription' => 'Conheça os serviços de irrigação, saneamento e monitoramento hidrológico.',
        ]);
    }

    public function serviceDetail(string $slug): View
    {
        $service = Service::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('site.service-detail', [
            'service' => $service,
            'metaTitle' => $service->title.' | '.Setting::value('seo_default_title', 'Felestrino Soluções'),
            'metaDescription' => $service->excerpt ?: Setting::value('seo_default_description', ''),
        ]);
    }
}
