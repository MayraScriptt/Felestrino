<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionRequest;
use App\Models\Page;
use App\Models\Section;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    public function index(): View
    {
        return view('admin.sections.index', [
            'sections' => Section::query()->with('page')->orderBy('display_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.sections.create', [
            'section' => new Section(),
            'pages' => Page::query()->orderBy('title')->get(),
        ]);
    }

    public function store(SectionRequest $request): RedirectResponse
    {
        $payload = $this->payload($request);

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('sections', 'public');
        }

        Section::query()->create($payload);
        SiteCache::bump();

        return redirect()->route('admin.sections.index')->with('status', 'Secao criada com sucesso.');
    }

    public function show(Section $section): RedirectResponse
    {
        return redirect()->route('admin.sections.edit', $section);
    }

    public function edit(Section $section): View
    {
        return view('admin.sections.edit', [
            'section' => $section,
            'pages' => Page::query()->orderBy('title')->get(),
        ]);
    }

    public function update(SectionRequest $request, Section $section): RedirectResponse
    {
        $payload = $this->payload($request);

        if ($request->hasFile('image')) {
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }
            $payload['image_path'] = $request->file('image')->store('sections', 'public');
        }

        $section->update($payload);
        SiteCache::bump();

        return redirect()->route('admin.sections.index')->with('status', 'Secao atualizada com sucesso.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();
        SiteCache::bump();

        return redirect()->route('admin.sections.index')->with('status', 'Secao removida com sucesso.');
    }

    private function payload(SectionRequest $request): array
    {
        $payload = $request->safe()->only([
            'page_id',
            'title',
            'section_key',
            'subtitle',
            'content',
            'display_order',
        ]);
        $payload['is_published'] = (bool) $request->boolean('is_published');

        return $payload;
    }
}
