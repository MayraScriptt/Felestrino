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
                'media_positions' => [],
            ]);
        }

        return view('sobre_a_empresa.edit', [
            'title' => 'Sobre_a_empresa',
            'aboutPage' => $aboutPage,
            'loadErrors' => $loadErrors,
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $payload = $request->validate([
            'content' => ['nullable', 'string'],
            'media_positions' => ['sometimes', 'array'],
        ]);

        $aboutPage = $this->getOrCreateAboutPage();
        $nextMediaPositions = array_key_exists('media_positions', $payload)
            ? $this->normalizeMediaPositions($payload['media_positions'])
            : $aboutPage->media_positions;

        $aboutPage->update([
            'content' => array_key_exists('content', $payload) ? $payload['content'] : null,
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
}
