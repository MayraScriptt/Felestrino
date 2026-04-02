<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionRequest;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        return view('admin.sections.index', [
            'sections' => Section::query()->with('page')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.sections.create', [
            'pages' => Page::query()->orderBy('title')->get(),
        ]);
    }

    public function store(SectionRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('sections', 'public');
        }

        Section::query()->create($payload);
        $this->clearCache();

        return redirect()->route('admin.sections.index')->with('status', 'Seção criada com sucesso.');
    }

    public function show(Section $section): View
    {
        return view('admin.sections.show', ['section' => $section->load('page')]);
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
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }

            $payload['image_path'] = $request->file('image')->store('sections', 'public');
        }

        $section->update($payload);
        $this->clearCache();

        return redirect()->route('admin.sections.index')->with('status', 'Seção atualizada com sucesso.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();
        $this->clearCache();

        return redirect()->route('admin.sections.index')->with('status', 'Seção removida com sucesso.');
    }

    private function clearCache(): void
    {
        Cache::forget('site.page.home');
        Cache::forget('site.page.about');
    }
}
