<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::query()->orderBy('display_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.create', [
            'page' => new Page(),
        ]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        Page::query()->create($this->payload($request));
        SiteCache::bump();

        return redirect()->route('admin.pages.index')->with('status', 'Pagina criada com sucesso.');
    }

    public function show(Page $page): RedirectResponse
    {
        return redirect()->route('admin.pages.edit', $page);
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($this->payload($request));
        SiteCache::bump();

        return redirect()->route('admin.pages.index')->with('status', 'Pagina atualizada com sucesso.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        SiteCache::bump();

        return redirect()->route('admin.pages.index')->with('status', 'Pagina removida com sucesso.');
    }

    private function payload(PageRequest $request): array
    {
        $payload = $request->safe()->only([
            'title',
            'slug',
            'meta_title',
            'meta_description',
            'content',
            'display_order',
        ]);
        $payload['is_published'] = (bool) $request->boolean('is_published');

        return $payload;
    }
}
