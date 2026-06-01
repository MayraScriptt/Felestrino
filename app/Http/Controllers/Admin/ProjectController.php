<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\ProjectPage;
use App\Models\ProjectTempMedia;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function edit(): View
    {
        $projectPage = ProjectPage::query()->first() ?? new ProjectPage();
        $projects = Project::query()->orderBy('display_order')->orderBy('id')->get();

        return view('admin.projetos.edit', [
            'title' => 'Projetos',
            'projectPage' => $projectPage,
            'projects' => $projects,
        ]);
    }

    public function updatePage(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'title' => ['nullable', 'string', 'max:140'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'banner_file' => ['sometimes', 'nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'banner_remove' => ['sometimes', 'nullable', 'boolean'],
            'banner_position_x' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'banner_position_y' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $projectPage = ProjectPage::query()->first();
        if (! $projectPage instanceof ProjectPage) {
            $projectPage = ProjectPage::query()->create([
                'title' => null,
                'subtitle' => null,
                'description' => null,
                'banner_path' => null,
                'banner_position_x' => 50,
                'banner_position_y' => 50,
            ]);
        }

        $oldBannerPath = $projectPage->banner_path;
        $nextBannerPath = $oldBannerPath;
        $removeRequested = array_key_exists('banner_remove', $payload) ? (bool) $payload['banner_remove'] : false;

        if ($request->hasFile('banner_file')) {
            $bannerFile = $request->file('banner_file');
            if ($bannerFile instanceof UploadedFile) {
                $nextBannerPath = $this->storeOptimizedImage($bannerFile, 'imagens/banners/projetos');
                if (is_string($oldBannerPath) && trim($oldBannerPath) !== '' && $oldBannerPath !== $nextBannerPath) {
                    $this->deleteImage($oldBannerPath);
                }
            }
        } elseif ($removeRequested) {
            if (is_string($oldBannerPath) && trim($oldBannerPath) !== '') {
                $this->deleteImage($oldBannerPath);
            }
            $nextBannerPath = null;
        }

        $projectPage->update([
            'title' => $payload['title'] ?? null,
            'subtitle' => $payload['subtitle'] ?? null,
            'description' => $payload['description'] ?? null,
            'banner_path' => $nextBannerPath,
            'banner_position_x' => array_key_exists('banner_position_x', $payload) ? ($payload['banner_position_x'] ?? 50) : ($projectPage->banner_position_x ?? 50),
            'banner_position_y' => array_key_exists('banner_position_y', $payload) ? ($payload['banner_position_y'] ?? 50) : ($projectPage->banner_position_y ?? 50),
        ]);

        SiteCache::bump();

        return redirect()->route('admin.projects.edit')->with('status', 'Página de projetos atualizada com sucesso.');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $payload = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'is_active' => ['nullable', 'boolean'],
            'draft_token' => ['nullable', 'string', 'max:64'],
        ]);

        $draftToken = array_key_exists('draft_token', $payload) && is_string($payload['draft_token'])
            ? trim($payload['draft_token'])
            : '';

        $project = DB::transaction(function () use ($payload, $draftToken) {
            $nextOrder = (int) (Project::query()->max('display_order') ?? 0) + 1;
            $slug = $this->uniqueSlug((string) $payload['title'], null);

            $project = Project::query()->create([
                'title' => $payload['title'],
                'subtitle' => $payload['subtitle'] ?? null,
                'description' => $payload['description'] ?? null,
                'slug' => $slug,
                'button_text' => $payload['button_text'] ?? 'Ver projeto',
                'display_order' => $nextOrder,
                'is_active' => (bool) ($payload['is_active'] ?? true),
            ]);

            if ($draftToken !== '') {
                $this->consumeDraftMedia($draftToken, $project);
            }

            return $project;
        });

        SiteCache::bump();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'project' => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'edit_url' => route('admin.projects.project.edit', $project),
                ],
            ]);
        }

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Projeto criado com sucesso.');
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $payload = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'max:190', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'banner_file' => ['sometimes', 'nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $nextDisplayOrder = array_key_exists('display_order', $payload)
            ? (int) $payload['display_order']
            : (int) $project->display_order;

        DB::transaction(function () use ($request, $payload, $project, $nextDisplayOrder) {
            $oldBannerPath = $project->banner_path;
            $nextBannerPath = $oldBannerPath;
            if ($request->hasFile('banner_file')) {
                $bannerFile = $request->file('banner_file');
                if ($bannerFile instanceof UploadedFile) {
                    $nextBannerPath = $this->storeOptimizedImage($bannerFile, 'imagens/banners/projetos/'.$project->id);
                    if (is_string($oldBannerPath) && trim($oldBannerPath) !== '' && $oldBannerPath !== $nextBannerPath) {
                        $this->deleteImage($oldBannerPath);
                    }
                }
            }

            $project->update([
                'title' => $payload['title'],
                'subtitle' => $payload['subtitle'] ?? null,
                'description' => $payload['description'] ?? null,
                'slug' => $this->uniqueSlug($payload['slug'], $project->id),
                'button_text' => $payload['button_text'] ?? 'Ver projeto',
                'banner_path' => $nextBannerPath,
                'is_active' => (bool) ($payload['is_active'] ?? false),
            ]);

            $this->reorderProjects($project->id, $nextDisplayOrder);
        });

        SiteCache::bump();

        return redirect()->route('admin.projects.edit')->with('status', 'Projeto atualizado com sucesso.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        foreach ($project->images as $image) {
            if (($image->type ?? 'image') === 'image' && is_string($image->image_path) && trim($image->image_path) !== '') {
                $this->deleteImage($image->image_path);
            }
        }
        if (is_string($project->banner_path) && trim($project->banner_path) !== '') {
            $this->deleteImage($project->banner_path);
        }
        $project->delete();
        $this->normalizeProjectOrders();

        SiteCache::bump();

        return redirect()->route('admin.projects.edit')->with('status', 'Projeto removido com sucesso.');
    }

    public function editProject(Project $project): View
    {
        $project->load(['images' => fn ($query) => $query->orderBy('display_order')->orderBy('id')]);

        return view('admin.projetos.project_edit', [
            'title' => 'Editar projeto',
            'project' => $project,
        ]);
    }

    public function imageStore(Request $request, Project $project): RedirectResponse|JsonResponse
    {
        $payload = $request->validate([
            'file' => ['sometimes', 'nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'youtube_url' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $nextOrder = (int) (ProjectMedia::query()->where('project_id', $project->id)->max('display_order') ?? 0) + 1;

        $path = null;
        $type = null;
        $youtubeId = null;
        $youtubeUrl = null;

        if ($request->hasFile('file')) {
            /** @var UploadedFile|null $file */
            $file = $request->file('file');
            if ($file instanceof UploadedFile) {
                $path = $this->storeOptimizedImage($file, 'imagens/projetos');
                $type = 'image';
            }
        } elseif (array_key_exists('youtube_url', $payload) && is_string($payload['youtube_url']) && trim($payload['youtube_url']) !== '') {
            $normalized = $this->normalizeYouTubeUrl($payload['youtube_url']);
            if (! $normalized) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Link do YouTube inválido.',
                        'errors' => ['youtube_url' => ['Link do YouTube inválido.']],
                    ], 422);
                }
                return redirect()
                    ->route('admin.projects.project.edit', $project)
                    ->withErrors(['youtube_url' => 'Link do YouTube inválido.'])
                    ->withInput();
            }
            $type = 'youtube';
            $youtubeId = $normalized['id'];
            $youtubeUrl = $normalized['url'];
        }

        if (! is_string($type) || $type === '') {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Envie uma imagem ou informe um link do YouTube.',
                    'errors' => ['file' => ['Envie uma imagem ou informe um link do YouTube.']],
                ], 422);
            }
            return redirect()
                ->route('admin.projects.project.edit', $project)
                ->withErrors(['file' => 'Envie uma imagem ou informe um link do YouTube.'])
                ->withInput();
        }

        $media = ProjectMedia::query()->create([
            'project_id' => $project->id,
            'type' => $type,
            'image_path' => $path,
            'youtube_id' => $youtubeId,
            'youtube_url' => $youtubeUrl,
            'description' => $payload['description'] ?? null,
            'display_order' => (int) ($payload['display_order'] ?? $nextOrder),
            'is_active' => (bool) ($payload['is_active'] ?? true),
        ]);

        SiteCache::bump();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'media' => [
                    'id' => $media->id,
                    'type' => $media->type,
                    'display_order' => $media->display_order,
                ],
            ]);
        }

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Imagem adicionada ao projeto com sucesso.');
    }

    public function tempMediaStore(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'draft_token' => ['required', 'string', 'max:64'],
            'file' => ['sometimes', 'nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'youtube_url' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $draftToken = trim((string) $payload['draft_token']);
        if ($draftToken === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Token inválido.',
                'errors' => ['draft_token' => ['Token inválido.']],
            ], 422);
        }

        $nextOrder = (int) (ProjectTempMedia::query()->where('draft_token', $draftToken)->max('display_order') ?? 0) + 1;

        $path = null;
        $type = null;
        $youtubeId = null;
        $youtubeUrl = null;

        if ($request->hasFile('file')) {
            /** @var UploadedFile|null $file */
            $file = $request->file('file');
            if ($file instanceof UploadedFile) {
                $path = $this->storeOptimizedImage($file, 'imagens/temp/projetos/'.$draftToken);
                $type = 'image';
            }
        } elseif (array_key_exists('youtube_url', $payload) && is_string($payload['youtube_url']) && trim($payload['youtube_url']) !== '') {
            $normalized = $this->normalizeYouTubeUrl($payload['youtube_url']);
            if (! $normalized) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Link do YouTube inválido.',
                    'errors' => ['youtube_url' => ['Link do YouTube inválido.']],
                ], 422);
            }
            $type = 'youtube';
            $youtubeId = $normalized['id'];
            $youtubeUrl = $normalized['url'];
        }

        if (! is_string($type) || $type === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Envie uma imagem ou informe um link do YouTube.',
                'errors' => ['file' => ['Envie uma imagem ou informe um link do YouTube.']],
            ], 422);
        }

        $media = ProjectTempMedia::query()->create([
            'draft_token' => $draftToken,
            'type' => $type,
            'image_path' => $path,
            'youtube_id' => $youtubeId,
            'youtube_url' => $youtubeUrl,
            'description' => $payload['description'] ?? null,
            'display_order' => (int) ($payload['display_order'] ?? $nextOrder),
            'is_active' => (bool) ($payload['is_active'] ?? true),
        ]);

        return response()->json([
            'ok' => true,
            'media' => [
                'id' => $media->id,
                'type' => $media->type,
                'display_order' => $media->display_order,
            ],
        ]);
    }

    public function tempMediaUpdate(Request $request, ProjectTempMedia $media): JsonResponse
    {
        $payload = $request->validate([
            'draft_token' => ['required', 'string', 'max:64'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $draftToken = trim((string) $payload['draft_token']);
        if ($draftToken === '' || $media->draft_token !== $draftToken) {
            return response()->json([
                'ok' => false,
                'message' => 'Item não encontrado.',
            ], 404);
        }

        if (array_key_exists('description', $payload)) {
            $media->update([
                'description' => $payload['description'] ?? null,
            ]);
        }

        return response()->json([
            'ok' => true,
            'media' => [
                'id' => $media->id,
                'description' => $media->description,
            ],
        ]);
    }

    public function tempMediaDestroy(Request $request, ProjectTempMedia $media): JsonResponse
    {
        $payload = $request->validate([
            'draft_token' => ['required', 'string', 'max:64'],
        ]);

        $draftToken = trim((string) $payload['draft_token']);
        if ($draftToken === '' || $media->draft_token !== $draftToken) {
            return response()->json([
                'ok' => false,
                'message' => 'Item não encontrado.',
            ], 404);
        }

        $type = (string) ($media->type ?? 'image');
        if ($type === 'image') {
            $path = is_string($media->image_path) ? trim($media->image_path) : '';
            if ($path !== '') {
                $this->deleteImage($path);
            }
        }

        $media->delete();

        $draftDir = public_path('imagens/temp/projetos/'.$draftToken);
        if (is_dir($draftDir)) {
            $leftovers = @scandir($draftDir);
            if (is_array($leftovers) && count(array_diff($leftovers, ['.', '..'])) === 0) {
                @rmdir($draftDir);
            }
        }

        return response()->json([
            'ok' => true,
            'media' => [
                'id' => $media->id,
            ],
        ]);
    }

    public function imageUpdate(Request $request, Project $project, ProjectMedia $image): RedirectResponse|JsonResponse
    {
        $this->ensureImageBelongsToProject($project, $image);

        $payload = $request->validate([
            'youtube_url' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $updates = [
            'description' => array_key_exists('description', $payload) ? ($payload['description'] ?? null) : $image->description,
            'display_order' => array_key_exists('display_order', $payload) ? (int) $payload['display_order'] : (int) $image->display_order,
            'is_active' => array_key_exists('is_active', $payload) ? (bool) $payload['is_active'] : (bool) $image->is_active,
        ];

        if (($image->type ?? 'image') === 'youtube' && array_key_exists('youtube_url', $payload)) {
            $url = is_string($payload['youtube_url']) ? trim($payload['youtube_url']) : '';
            if ($url === '') {
                return redirect()
                    ->route('admin.projects.project.edit', $project)
                    ->withErrors(['youtube_url' => 'Informe um link do YouTube.']);
            }
            $normalized = $this->normalizeYouTubeUrl($url);
            if (! $normalized) {
                return redirect()
                    ->route('admin.projects.project.edit', $project)
                    ->withErrors(['youtube_url' => 'Link do YouTube inválido.']);
            }
            $updates['youtube_id'] = $normalized['id'];
            $updates['youtube_url'] = $normalized['url'];
        }

        $image->update($updates);
        $image->refresh();

        SiteCache::bump();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'media' => [
                    'id' => $image->id,
                    'description' => $image->description,
                    'is_active' => (bool) $image->is_active,
                    'display_order' => (int) $image->display_order,
                ],
            ]);
        }

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Imagem do projeto atualizada com sucesso.');
    }

    public function imageDestroy(Project $project, ProjectMedia $image): RedirectResponse
    {
        $this->ensureImageBelongsToProject($project, $image);

        if (($image->type ?? 'image') === 'image' && is_string($image->image_path) && trim($image->image_path) !== '') {
            $this->deleteImage($image->image_path);
        }
        $image->delete();

        SiteCache::bump();

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Imagem removida do projeto com sucesso.');
    }

    public function mediaReorder(Request $request, Project $project): JsonResponse
    {
        $payload = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:500'],
            'ids.*' => ['integer'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $payload['ids'])));
        if (count($ids) === 0) {
            return response()->json(['ok' => false, 'message' => 'Lista vazia.'], 422);
        }

        $existing = ProjectMedia::query()
            ->where('project_id', $project->id)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $existingSet = array_fill_keys($existing, true);
        foreach ($ids as $id) {
            if (! array_key_exists($id, $existingSet)) {
                return response()->json(['ok' => false, 'message' => 'Item inválido.'], 422);
            }
        }

        DB::transaction(function () use ($project, $ids) {
            foreach ($ids as $index => $id) {
                ProjectMedia::query()
                    ->where('project_id', $project->id)
                    ->where('id', $id)
                    ->update(['display_order' => $index + 1]);
            }
        });

        SiteCache::bump();

        return response()->json(['ok' => true]);
    }

    private function ensureImageBelongsToProject(Project $project, ProjectMedia $image): void
    {
        abort_unless((int) $image->project_id === (int) $project->id, 404);
    }

    private function normalizeYouTubeUrl(string $url): ?array
    {
        $trimmed = trim($url);
        if ($trimmed === '') {
            return null;
        }

        $id = null;

        if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $trimmed, $m)) {
            $id = $m[1];
        } elseif (preg_match('~youtube\.com/.*[?&]v=([A-Za-z0-9_-]{11})~', $trimmed, $m)) {
            $id = $m[1];
        } elseif (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{11})~', $trimmed, $m)) {
            $id = $m[1];
        } elseif (preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]{11})~', $trimmed, $m)) {
            $id = $m[1];
        }

        if (! is_string($id) || $id === '') {
            return null;
        }

        return [
            'id' => $id,
            'url' => 'https://www.youtube.com/watch?v='.$id,
        ];
    }

    private function reorderProjects(int $projectId, int $desiredDisplayOrder): void
    {
        $ids = Project::query()
            ->orderBy('display_order')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $filtered = [];
        foreach ($ids as $id) {
            $parsed = (int) $id;
            if ($parsed === $projectId) {
                continue;
            }
            $filtered[] = $parsed;
        }

        $count = count($filtered);
        $desired = max(1, $desiredDisplayOrder);
        $targetIndex = min(max(0, $desired - 1), $count);

        array_splice($filtered, $targetIndex, 0, [$projectId]);

        foreach ($filtered as $index => $id) {
            Project::query()->where('id', $id)->update([
                'display_order' => $index + 1,
            ]);
        }
    }

    private function normalizeProjectOrders(): void
    {
        $ids = Project::query()
            ->orderBy('display_order')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        foreach ($ids as $index => $id) {
            Project::query()->where('id', (int) $id)->update([
                'display_order' => $index + 1,
            ]);
        }
    }

    private function uniqueSlug(?string $value, ?int $ignoreId): string
    {
        $base = Str::slug((string) $value);
        if ($base === '') {
            $base = 'projeto';
        }

        $slug = $base;
        $counter = 1;

        while (
            Project::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $counter++;
            $slug = "{$base}-{$counter}";
        }

        return $slug;
    }

    private function storeOptimizedImage(UploadedFile $file, string $relativeDir): string
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (! in_array($extension, $allowed, true)) {
            $extension = 'jpg';
        }

        $relativeDir = trim($relativeDir, '/\\');
        if ($relativeDir === '') {
            $relativeDir = 'imagens/projetos';
        }
        $absoluteDir = public_path($relativeDir);
        if (! is_dir($absoluteDir)) {
            @mkdir($absoluteDir, 0755, true);
        }

        $baseName = pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($baseName);
        if ($slug === '') {
            $slug = 'projeto';
        }
        $unique = Str::lower(Str::random(10));

        $gdAvailable = function_exists('imagecreatefromjpeg')
            && function_exists('imagescale')
            && function_exists('imagewebp');

        if (! $gdAvailable || $extension === 'gif') {
            $fileName = "{$slug}-{$unique}.{$extension}";
            $file->move($absoluteDir, $fileName);

            return $relativeDir.'/'.$fileName;
        }

        $tmpPath = $file->getRealPath();
        if (! is_string($tmpPath) || $tmpPath === '') {
            $fileName = "{$slug}-{$unique}.{$extension}";
            $file->move($absoluteDir, $fileName);

            return $relativeDir.'/'.$fileName;
        }

        $info = @getimagesize($tmpPath);
        if (! is_array($info) || ! isset($info[0], $info[1], $info['mime'])) {
            $fileName = "{$slug}-{$unique}.{$extension}";
            $file->move($absoluteDir, $fileName);

            return $relativeDir.'/'.$fileName;
        }

        $mime = (string) $info['mime'];
        $src = null;
        if ($mime === 'image/jpeg') {
            $src = @imagecreatefromjpeg($tmpPath);
        } elseif ($mime === 'image/png') {
            $src = @imagecreatefrompng($tmpPath);
        } elseif ($mime === 'image/webp') {
            $src = @imagecreatefromwebp($tmpPath);
        }

        if (! $src) {
            $fileName = "{$slug}-{$unique}.{$extension}";
            $file->move($absoluteDir, $fileName);

            return $relativeDir.'/'.$fileName;
        }

        $maxWidth = 1920;
        $width = (int) $info[0];
        $targetWidth = $width > $maxWidth ? $maxWidth : $width;
        $scaled = $src;
        if ($targetWidth !== $width) {
            $scaled = imagescale($src, $targetWidth);
        }

        $fileName = "{$slug}-{$unique}.webp";
        $targetFullPath = $absoluteDir.DIRECTORY_SEPARATOR.$fileName;

        $ok = @imagewebp($scaled, $targetFullPath, 82);
        if (is_resource($src) || (is_object($src) && get_class($src) === 'GdImage')) {
            @imagedestroy($src);
        }
        if ((is_resource($scaled) || (is_object($scaled) && get_class($scaled) === 'GdImage')) && $scaled !== $src) {
            @imagedestroy($scaled);
        }

        if (! $ok) {
            $fallbackName = "{$slug}-{$unique}.{$extension}";
            $file->move($absoluteDir, $fallbackName);

            return $relativeDir.'/'.$fallbackName;
        }

        return $relativeDir.'/'.$fileName;
    }

    private function deleteImage(string $path): void
    {
        if (str_starts_with($path, 'imagens/') || str_starts_with($path, 'images/')) {
            $fullPath = public_path($path);
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }

            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function consumeDraftMedia(string $draftToken, Project $project): void
    {
        $items = ProjectTempMedia::query()
            ->where('draft_token', $draftToken)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            return;
        }

        $baseOrder = (int) (ProjectMedia::query()->where('project_id', $project->id)->max('display_order') ?? 0);

        foreach ($items as $index => $item) {
            $type = $item->type ?? 'image';
            $imagePath = null;

            if ($type === 'image') {
                $imagePath = $this->movePublicImage($item->image_path, 'imagens/projetos');
            }

            ProjectMedia::query()->create([
                'project_id' => $project->id,
                'type' => $type,
                'image_path' => $imagePath,
                'youtube_id' => $type === 'youtube' ? $item->youtube_id : null,
                'youtube_url' => $type === 'youtube' ? $item->youtube_url : null,
                'description' => $item->description,
                'display_order' => $baseOrder + $index + 1,
                'is_active' => (bool) ($item->is_active ?? true),
            ]);

            $item->delete();
        }

        $draftDir = public_path('imagens/temp/projetos/'.$draftToken);
        if (is_dir($draftDir)) {
            $leftovers = @scandir($draftDir);
            if (is_array($leftovers) && count(array_diff($leftovers, ['.', '..'])) === 0) {
                @rmdir($draftDir);
            }
        }
    }

    private function movePublicImage(?string $path, string $targetRelativeDir): ?string
    {
        if (! is_string($path)) {
            return null;
        }
        $path = trim($path);
        if ($path === '') {
            return null;
        }
        if (! str_starts_with($path, 'imagens/') && ! str_starts_with($path, 'images/')) {
            return $path;
        }

        $sourceFullPath = public_path($path);
        if (! is_file($sourceFullPath)) {
            return $path;
        }

        $targetRelativeDir = trim($targetRelativeDir, '/\\');
        if ($targetRelativeDir === '') {
            $targetRelativeDir = 'imagens/projetos';
        }
        $targetFullDir = public_path($targetRelativeDir);
        if (! is_dir($targetFullDir)) {
            @mkdir($targetFullDir, 0755, true);
        }

        $fileName = basename($path);
        $targetFullPath = $targetFullDir.DIRECTORY_SEPARATOR.$fileName;
        if (is_file($targetFullPath)) {
            $base = pathinfo($fileName, PATHINFO_FILENAME);
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $unique = Str::lower(Str::random(6));
            $fileName = $ext !== '' ? "{$base}-{$unique}.{$ext}" : "{$base}-{$unique}";
            $targetFullPath = $targetFullDir.DIRECTORY_SEPARATOR.$fileName;
        }

        $moved = @rename($sourceFullPath, $targetFullPath);
        if (! $moved) {
            $copied = @copy($sourceFullPath, $targetFullPath);
            if ($copied) {
                @unlink($sourceFullPath);
            } else {
                return $path;
            }
        }

        return $targetRelativeDir.'/'.$fileName;
    }
}
