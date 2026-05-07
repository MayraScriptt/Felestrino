<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectPage;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        ]);

        $projectPage = ProjectPage::query()->first();
        if (! $projectPage instanceof ProjectPage) {
            $projectPage = ProjectPage::query()->create([
                'title' => null,
                'subtitle' => null,
                'description' => null,
            ]);
        }

        $projectPage->update([
            'title' => $payload['title'] ?? null,
            'subtitle' => $payload['subtitle'] ?? null,
            'description' => $payload['description'] ?? null,
        ]);

        SiteCache::bump();

        return redirect()->route('admin.projects.edit')->with('status', 'Página de projetos atualizada com sucesso.');
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'max:190', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $nextOrder = (int) (Project::query()->max('display_order') ?? 0) + 1;
        $baseSlug = $payload['slug'] ?? Str::slug((string) $payload['title']);
        $slug = $this->uniqueSlug($baseSlug, null);

        $project = Project::query()->create([
            'title' => $payload['title'],
            'subtitle' => $payload['subtitle'] ?? null,
            'description' => $payload['description'] ?? null,
            'slug' => $slug,
            'button_text' => $payload['button_text'] ?? 'Ver projeto',
            'display_order' => $nextOrder,
            'is_active' => (bool) ($payload['is_active'] ?? true),
        ]);

        SiteCache::bump();

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
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $project->update([
            'title' => $payload['title'],
            'subtitle' => $payload['subtitle'] ?? null,
            'description' => $payload['description'] ?? null,
            'slug' => $this->uniqueSlug($payload['slug'], $project->id),
            'button_text' => $payload['button_text'] ?? 'Ver projeto',
            'display_order' => (int) ($payload['display_order'] ?? $project->display_order),
            'is_active' => (bool) ($payload['is_active'] ?? false),
        ]);

        SiteCache::bump();

        return redirect()->route('admin.projects.edit')->with('status', 'Projeto atualizado com sucesso.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        foreach ($project->images as $image) {
            $this->deleteImage($image->image_path);
        }
        $project->delete();

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

    public function imageStore(Request $request, Project $project): RedirectResponse
    {
        $payload = $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        /** @var UploadedFile $file */
        $file = $payload['file'];
        $path = $this->storeOptimizedImage($file, 'imagens/projetos');
        $nextOrder = (int) (ProjectImage::query()->where('project_id', $project->id)->max('display_order') ?? 0) + 1;

        ProjectImage::query()->create([
            'project_id' => $project->id,
            'image_path' => $path,
            'description' => $payload['description'] ?? null,
            'display_order' => (int) ($payload['display_order'] ?? $nextOrder),
            'is_active' => (bool) ($payload['is_active'] ?? true),
        ]);

        SiteCache::bump();

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Imagem adicionada ao projeto com sucesso.');
    }

    public function imageUpdate(Request $request, Project $project, ProjectImage $image): RedirectResponse
    {
        $this->ensureImageBelongsToProject($project, $image);

        $payload = $request->validate([
            'description' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $image->update([
            'description' => $payload['description'] ?? null,
            'display_order' => (int) ($payload['display_order'] ?? $image->display_order),
            'is_active' => (bool) ($payload['is_active'] ?? false),
        ]);

        SiteCache::bump();

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Imagem do projeto atualizada com sucesso.');
    }

    public function imageDestroy(Project $project, ProjectImage $image): RedirectResponse
    {
        $this->ensureImageBelongsToProject($project, $image);

        $this->deleteImage($image->image_path);
        $image->delete();

        SiteCache::bump();

        return redirect()->route('admin.projects.project.edit', $project)->with('status', 'Imagem removida do projeto com sucesso.');
    }

    private function ensureImageBelongsToProject(Project $project, ProjectImage $image): void
    {
        abort_unless((int) $image->project_id === (int) $project->id, 404);
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
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
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

        if (! $gdAvailable) {
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
}
