<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaItemRequest;
use App\Models\MediaCategory;
use App\Models\MediaItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaItemController extends Controller
{
    public function index(): View
    {
        return view('admin.media-items.index', [
            'mediaItems' => MediaItem::query()->with('mediaCategory')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.media-items.create', [
            'categories' => MediaCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(MediaItemRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $file = $request->file('file');
        $payload['file_path'] = $file->store('gallery', 'public');
        $payload['size'] = $file->getSize();
        $payload['is_active'] = $request->boolean('is_active');

        MediaItem::query()->create($payload);
        Cache::forget('site.gallery');

        return redirect()->route('admin.media-items.index')->with('status', 'Mídia cadastrada com sucesso.');
    }

    public function show(MediaItem $mediaItem): View
    {
        return view('admin.media-items.show', ['mediaItem' => $mediaItem->load('mediaCategory')]);
    }

    public function edit(MediaItem $mediaItem): View
    {
        return view('admin.media-items.edit', [
            'mediaItem' => $mediaItem,
            'categories' => MediaCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(MediaItemRequest $request, MediaItem $mediaItem): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($mediaItem->file_path);
            $file = $request->file('file');
            $payload['file_path'] = $file->store('gallery', 'public');
            $payload['size'] = $file->getSize();
        }

        $mediaItem->update($payload);
        Cache::forget('site.gallery');

        return redirect()->route('admin.media-items.index')->with('status', 'Mídia atualizada com sucesso.');
    }

    public function destroy(MediaItem $mediaItem): RedirectResponse
    {
        Storage::disk('public')->delete($mediaItem->file_path);
        $mediaItem->delete();
        Cache::forget('site.gallery');

        return redirect()->route('admin.media-items.index')->with('status', 'Mídia removida com sucesso.');
    }
}
