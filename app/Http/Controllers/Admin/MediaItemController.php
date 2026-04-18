<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaItemRequest;
use App\Models\MediaItem;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class MediaItemController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', [
            'mediaItems' => MediaItem::query()->orderBy('category')->orderBy('display_order')->paginate(24),
        ]);
    }

    public function create(): View
    {
        return view('admin.media.create', [
            'mediaItem' => new MediaItem(),
        ]);
    }

    public function store(MediaItemRequest $request): RedirectResponse
    {
        if (! $request->hasFile('file')) {
            return back()->withErrors(['file' => 'O arquivo e obrigatorio.'])->withInput();
        }

        $file = $request->file('file');
        $path = $file->store('gallery', 'public');

        MediaItem::query()->create([
            'title' => $request->string('title')->toString(),
            'category' => $request->string('category')->toString(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize(),
            'is_video' => (bool) $request->boolean('is_video'),
            'display_order' => (int) $request->integer('display_order', 0),
        ]);

        SiteCache::bump();

        return redirect()->route('admin.media.index')->with('status', 'Midia enviada com sucesso.');
    }

    public function show(MediaItem $mediaItem): RedirectResponse
    {
        return redirect()->route('admin.media.edit', $mediaItem);
    }

    public function edit(MediaItem $mediaItem): View
    {
        return view('admin.media.edit', compact('mediaItem'));
    }

    public function update(MediaItemRequest $request, MediaItem $mediaItem): RedirectResponse
    {
        $payload = [
            'title' => $request->string('title')->toString(),
            'category' => $request->string('category')->toString(),
            'is_video' => (bool) $request->boolean('is_video'),
            'display_order' => (int) $request->integer('display_order', 0),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Storage::disk('public')->delete($mediaItem->file_path);
            $payload['file_path'] = $file->store('gallery', 'public');
            $payload['mime_type'] = $file->getMimeType() ?: 'application/octet-stream';
            $payload['file_size'] = $file->getSize();
        }

        $mediaItem->update($payload);
        SiteCache::bump();

        return redirect()->route('admin.media.index')->with('status', 'Midia atualizada com sucesso.');
    }

    public function destroy(MediaItem $mediaItem): RedirectResponse
    {
        Storage::disk('public')->delete($mediaItem->file_path);
        $mediaItem->delete();
        SiteCache::bump();

        return redirect()->route('admin.media.index')->with('status', 'Midia removida com sucesso.');
    }
}
