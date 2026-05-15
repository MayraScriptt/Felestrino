<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\HomeCard;
use App\Models\HomeCarouselItem;
use App\Models\MediaItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectPage;
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
                ->first();

            return [
                'settings' => $this->settings(),
                'homePage' => $homePage,
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
            if ($slug === 'sobre') {
                $aboutPage = AboutPage::query()->first();
                $page = new Page([
                    'title' => 'Sobre a empresa',
                    'slug' => 'sobre',
                    'content' => $aboutPage?->content ?? '',
                    'is_published' => true,
                ]);

                return [
                    'settings' => $this->settings(),
                    'page' => $page,
                    'allowHtml' => true,
                    'banner_path' => $aboutPage?->banner_path,
                    'banner_subtitle' => $aboutPage?->banner_subtitle,
                    'banner_description' => $aboutPage?->banner_description,
                ];
            }

            $page = Page::query()
                ->where('slug', $slug)
                ->where('is_published', true)
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

    public function projects(): View
    {
        $data = Cache::remember(SiteCache::key('projects'), now()->addMinutes(30), function () {
            $projectPage = ProjectPage::query()->first();

            return [
                'settings' => $this->settings(),
                'projectPage' => $projectPage,
                'projects' => Project::query()
                    ->where('is_active', true)
                    ->with(['images' => fn ($query) => $query->where('is_active', true)->orderBy('display_order')->orderBy('id')])
                    ->orderBy('display_order')
                    ->orderBy('id')
                    ->get(),
            ];
        });

        $seo = [
            'title' => ($data['projectPage']?->title ?: 'Projetos').' | '.($data['settings']['company_name'] ?? 'Felestrino Solucoes'),
            'description' => $data['projectPage']?->subtitle ?: ($data['settings']['seo_description'] ?? ''),
        ];

        return view('site.projects', $data + ['seo' => $seo]);
    }

    public function project(Project $project): View
    {
        abort_unless($project->is_active, 404);

        $data = Cache::remember(SiteCache::key("project:{$project->id}"), now()->addMinutes(30), function () use ($project) {
            $loadedProject = Project::query()
                ->whereKey($project->id)
                ->where('is_active', true)
                ->with(['images' => fn ($query) => $query->where('is_active', true)->orderBy('display_order')->orderBy('id')])
                ->firstOrFail();

            return [
                'settings' => $this->settings(),
                'project' => $loadedProject,
            ];
        });

        $seo = [
            'title' => $data['project']->title.' | '.($data['settings']['company_name'] ?? 'Felestrino Solucoes'),
            'description' => $data['project']->subtitle ?: ($data['settings']['seo_description'] ?? ''),
        ];

        return view('site.project', $data + ['seo' => $seo]);
    }

    private function settings(): array
    {
        return [
            'company_name' => Setting::getValue('company_name', 'Felestrino Solucoes'),
            'tagline' => Setting::getValue('tagline', 'Tecnologia para agua e irrigacao'),
            'phone' => Setting::getValue('phone'),
            'phone2' => Setting::getValue('phone2'),
            'message' => Setting::getValue('message'),
            'email' => Setting::getValue('email'),
            'address' => Setting::getValue('address'),
            'about' => Setting::getValue('about'),
            'seo_title' => Setting::getValue('seo_title'),
            'seo_description' => Setting::getValue('seo_description'),
        ];
    }
}
