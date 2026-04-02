<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::query()->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(PageRequest $request): RedirectResponse
    {
        Page::query()->create([
            ...$request->validated(),
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->clearCache();

        return redirect()->route('admin.pages.index')->with('status', 'Página criada com sucesso.');
    }

    public function show(Page $page): View
    {
        return view('admin.pages.show', [
            'page' => $page->load('sections'),
        ]);
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update([
            ...$request->validated(),
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->clearCache();

        return redirect()->route('admin.pages.index')->with('status', 'Página atualizada com sucesso.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        $this->clearCache();

        return redirect()->route('admin.pages.index')->with('status', 'Página removida com sucesso.');
    }

    private function clearCache(): void
    {
        Cache::forget('site.page.home');
        Cache::forget('site.page.about');
    }
}
