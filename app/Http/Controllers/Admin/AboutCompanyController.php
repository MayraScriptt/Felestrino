<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AboutCompanyController extends Controller
{
    public function edit(): View
    {
        $loadErrors = [];
        try {
            $aboutPage = $this->getOrCreateAboutPage();
        } catch (\Throwable $e) {
            report($e);
            $loadErrors[] = 'Não foi possível carregar a página "Sobre a empresa". Verifique o banco de dados e as migrations.';
            $aboutPage = new AboutPage([
                'content' => null,
                'banner_path' => null,
                'banner_subtitle' => null,
                'banner_description' => null,
                'media_positions' => [],
            ]);
        }

        return view('admin.sobre_a_empresa.edit', [
            'title' => 'Sobre a empresa',
            'aboutPage' => $aboutPage,
            'loadErrors' => $loadErrors,
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $payload = $request->validate([
            'content' => ['nullable', 'string'],
            'media_positions' => ['sometimes', 'array'],
            'banner_file' => ['sometimes', 'nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'banner_remove' => ['sometimes', 'nullable', 'boolean'],
            'banner_subtitle' => ['sometimes', 'nullable', 'string', 'max:255'],
            'banner_description' => ['sometimes', 'nullable', 'string'],
        ]);

        $aboutPage = $this->getOrCreateAboutPage();
        $oldBannerPath = $aboutPage->banner_path;
        $nextBannerPath = $oldBannerPath;

        $removeRequested = array_key_exists('banner_remove', $payload) ? (bool) $payload['banner_remove'] : false;

        if ($request->hasFile('banner_file')) {
            $bannerFile = $request->file('banner_file');
            if ($bannerFile instanceof UploadedFile) {
                $nextBannerPath = $this->storeOptimizedImage($bannerFile, 'imagens/banners/sobre');
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

        $nextMediaPositions = array_key_exists('media_positions', $payload)
            ? $this->normalizeMediaPositions($payload['media_positions'])
            : $aboutPage->media_positions;

        $aboutPage->update([
            'content' => array_key_exists('content', $payload) ? $payload['content'] : null,
            'banner_path' => $nextBannerPath,
            'banner_subtitle' => array_key_exists('banner_subtitle', $payload) ? ($payload['banner_subtitle'] ?: null) : $aboutPage->banner_subtitle,
            'banner_description' => array_key_exists('banner_description', $payload) ? ($payload['banner_description'] ?: null) : $aboutPage->banner_description,
            'media_positions' => $nextMediaPositions,
        ]);

        SiteCache::bump();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Página "Sobre a empresa" atualizada com sucesso.',
            ]);
        }

        return redirect()->route('admin.about-company.edit')->with('status', 'Página "Sobre a empresa" atualizada com sucesso.');
    }

    public function mediaLayout(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'media_positions' => ['required', 'array'],
        ]);

        $aboutPage = $this->getOrCreateAboutPage();
        $normalized = $this->normalizeMediaPositions($payload['media_positions']);
        $aboutPage->update([
            'media_positions' => $normalized,
        ]);

        SiteCache::bump();

        return response()->json([
            'ok' => true,
            'message' => 'Posições da mídia atualizadas com sucesso.',
            'media_positions' => $normalized,
        ]);
    }

    public function upload(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        /** @var UploadedFile $file */
        $file = $payload['file'];

        $path = Storage::disk('public')->putFile('sobre_a_empresa', $file);

        return response()->json([
            'location' => asset('storage/'.$path),
        ]);
    }

    private function getOrCreateAboutPage(): AboutPage
    {
        $aboutPage = AboutPage::query()->first();
        if ($aboutPage instanceof AboutPage) {
            return $aboutPage;
        }

        return AboutPage::query()->create([
            'content' => null,
            'banner_path' => null,
            'banner_subtitle' => null,
            'banner_description' => null,
            'media_positions' => [],
        ]);
    }

    private function normalizeMediaPositions(mixed $positions): array
    {
        if (! is_array($positions)) {
            return [];
        }

        $normalized = [];

        foreach ($positions as $mediaId => $position) {
            if (! is_string($mediaId) || trim($mediaId) === '' || ! is_array($position)) {
                continue;
            }

            $left = array_key_exists('left', $position) ? (float) $position['left'] : 0.0;
            $top = array_key_exists('top', $position) ? (float) $position['top'] : 0.0;
            $width = array_key_exists('width', $position) ? (float) $position['width'] : 0.0;
            $height = array_key_exists('height', $position) ? (float) $position['height'] : 0.0;
            $zIndex = array_key_exists('z', $position) ? (int) $position['z'] : 1;

            $cleanKey = mb_substr(trim($mediaId), 0, 180);
            $normalized[$cleanKey] = [
                'left' => $this->clamp($left, 0, 1),
                'top' => $this->clamp($top, 0, 1),
                'width' => $this->clamp($width, 0, 1),
                'height' => $this->clamp($height, 0, 1),
                'z' => max(1, min($zIndex, 9999)),
            ];

            if (count($normalized) >= 150) {
                break;
            }
        }

        return $normalized;
    }

    private function clamp(float $value, float $min, float $max): float
    {
        if ($value < $min) {
            return $min;
        }

        if ($value > $max) {
            return $max;
        }

        return $value;
    }

    private function storeOptimizedImage(UploadedFile $file, string $relativeDir): string
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (! in_array($extension, $allowed, true)) {
            $extension = 'jpg';
        }

        $gdAvailable = function_exists('imagecreatefromjpeg')
            && function_exists('imagescale')
            && function_exists('imagewebp');

        $relativeDir = trim($relativeDir, '/\\');
        if ($relativeDir === '') {
            $relativeDir = 'imagens/banners';
        }
        $absoluteDir = public_path($relativeDir);
        if (! is_dir($absoluteDir)) {
            @mkdir($absoluteDir, 0755, true);
        }

        $baseName = pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($baseName);
        if ($slug === '') {
            $slug = 'banner';
        }
        $unique = Str::lower(Str::random(10));

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
}
