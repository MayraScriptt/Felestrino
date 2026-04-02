<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Models\Page;
use App\Models\Section;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'pages' => Page::query()->count(),
                'sections' => Section::query()->count(),
                'services' => Service::query()->count(),
                'media' => MediaItem::query()->count(),
            ],
        ]);
    }
}
