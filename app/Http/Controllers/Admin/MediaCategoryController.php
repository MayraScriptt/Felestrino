<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaCategoryRequest;
use App\Models\MediaCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MediaCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.media-categories.index', [
            'categories' => MediaCategory::query()->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.media-categories.create');
    }

    public function store(MediaCategoryRequest $request): RedirectResponse
    {
        MediaCategory::query()->create($request->validated());
        Cache::forget('site.gallery');

        return redirect()->route('admin.media-categories.index')->with('status', 'Categoria criada com sucesso.');
    }

    public function show(MediaCategory $mediaCategory): View
    {
        return view('admin.media-categories.show', ['category' => $mediaCategory->load('mediaItems')]);
    }

    public function edit(MediaCategory $mediaCategory): View
    {
        return view('admin.media-categories.edit', ['category' => $mediaCategory]);
    }

    public function update(MediaCategoryRequest $request, MediaCategory $mediaCategory): RedirectResponse
    {
        $mediaCategory->update($request->validated());
        Cache::forget('site.gallery');

        return redirect()->route('admin.media-categories.index')->with('status', 'Categoria atualizada com sucesso.');
    }

    public function destroy(MediaCategory $mediaCategory): RedirectResponse
    {
        $mediaCategory->delete();
        Cache::forget('site.gallery');

        return redirect()->route('admin.media-categories.index')->with('status', 'Categoria removida com sucesso.');
    }
}
