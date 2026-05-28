<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCard;
use App\Models\HomeCarouselItem;
use App\Models\HomeContentAudit;
use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function edit(): View
    {
        $loadErrors = [];
        $settings = collect();
        $carouselItems = collect();
        $cards = collect();
        $audits = collect();

        try {
            $settings = Setting::query()->whereIn('key', $this->keys())->pluck('value', 'key');
        } catch (\Throwable $e) {
            report($e);
            $loadErrors[] = 'Não foi possível carregar as configurações. Verifique o banco de dados e as migrations.';
        }

        try {
            $carouselItems = HomeCarouselItem::query()->orderBy('display_order')->orderBy('id')->get();
        } catch (\Throwable $e) {
            report($e);
            $loadErrors[] = 'Não foi possível carregar os itens do carrossel. Verifique se a tabela home_carousel_items existe e está migrada.';
        }

        try {
            $cards = HomeCard::query()->orderBy('display_order')->orderBy('id')->get();
        } catch (\Throwable $e) {
            report($e);
            $loadErrors[] = 'Não foi possível carregar os cards. Verifique se a tabela home_cards existe e está migrada.';
        }

        try {
            $audits = HomeContentAudit::query()->with('user')->latest()->limit(30)->get();
        } catch (\Throwable $e) {
            report($e);
            $loadErrors[] = 'Não foi possível carregar o histórico de alterações.';
        }

        return view('admin.home.edit', [
            'settings' => $settings,
            'carouselItems' => $carouselItems,
            'cards' => $cards,
            'audits' => $audits,
            'loadErrors' => $loadErrors,
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $rules = [
            'company_name' => ['sometimes', 'required', 'string', 'max:150'],
            'tagline' => ['sometimes', 'nullable', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:40'],
            'phone2' => ['sometimes', 'nullable', 'string', 'max:40'],
            'message' => ['sometimes', 'nullable', 'string', 'max:500'],
            'email' => ['sometimes', 'nullable', 'email', 'max:150'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'about' => ['sometimes', 'nullable', 'string'],
            'seo_title' => ['sometimes', 'nullable', 'string', 'max:160'],
            'seo_description' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];

        $payload = $request->validate($rules);

        $changes = [];
        foreach ($payload as $key => $value) {
            if (! in_array($key, $this->keys(), true)) {
                continue;
            }

            if (is_string($value) && trim($value) === '') {
                $value = null;
            }

            $before = Setting::getValue($key);

            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("setting:{$key}");

            if ($before != $value) {
                $changes[$key] = ['old' => $before, 'new' => $value];
            }
        }

        if ($changes !== []) {
            $this->audit($request, 'settings', null, 'updated', $changes);
            SiteCache::bump();
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Página inicial atualizada com sucesso.']);
        }

        return redirect()->route('admin.home.edit')->with('status', 'Página inicial atualizada com sucesso.');
    }

    public function faviconStore(Request $request): JsonResponse
    {
        /** @var UploadedFile|null $file */
        $file = $request->file('file');
        if (! $file instanceof UploadedFile) {
            return response()->json([
                'ok' => false,
                'message' => 'Envie um arquivo.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:png', 'max:5120'],
        ]);

        $before = [
            'favicon' => file_exists(public_path('imagens/favicon.png')),
            'apple_touch_icon' => file_exists(public_path('imagens/apple-touch-icon.png')),
        ];

        $this->storeFaviconAssets($file);

        $after = [
            'favicon' => file_exists(public_path('imagens/favicon.png')),
            'apple_touch_icon' => file_exists(public_path('imagens/apple-touch-icon.png')),
        ];

        $this->audit($request, 'settings', null, 'favicon_updated', [
            'before' => $before,
            'after' => $after,
        ]);

        SiteCache::bump();

        $faviconPath = public_path('imagens/favicon.png');
        $faviconVersion = is_file($faviconPath) ? (string) @filemtime($faviconPath) : (string) time();

        return response()->json([
            'ok' => true,
            'message' => 'Favicon atualizado com sucesso.',
            'favicon_url' => asset('imagens/favicon.png').'?v='.$faviconVersion,
            'apple_touch_icon_url' => asset('imagens/apple-touch-icon.png').'?v='.$faviconVersion,
        ]);
    }

    public function carouselStore(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'title' => ['nullable', 'string', 'max:100'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'url', 'max:2048', 'starts_with:http://,https://'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_url' => ['nullable', 'string', 'max:2048'],
        ]);

        /** @var UploadedFile $file */
        $file = $payload['file'];
        $path = $this->storeOptimizedImage($file, 'imagens/banners');

        $nextOrder = (int) (HomeCarouselItem::query()->max('display_order') ?? 0) + 1;

        $item = HomeCarouselItem::query()->create([
            'title' => $payload['title'] ?? null,
            'subtitle' => $payload['subtitle'] ?? null,
            'link_url' => $payload['link_url'] ?? null,
            'button_text' => $payload['button_text'] ?? null,
            'button_url' => $payload['button_url'] ?? null,
            'image_path' => $path,
            'display_order' => $nextOrder,
            'is_active' => true,
        ]);

        $this->audit($request, 'carousel', $item->id, 'created', [
            'title' => ['old' => null, 'new' => $item->title],
            'subtitle' => ['old' => null, 'new' => $item->subtitle],
            'link_url' => ['old' => null, 'new' => $item->link_url],
            'button_text' => ['old' => null, 'new' => $item->button_text],
            'button_url' => ['old' => null, 'new' => $item->button_url],
            'image_path' => ['old' => null, 'new' => $item->image_path],
            'display_order' => ['old' => null, 'new' => $item->display_order],
        ]);

        SiteCache::bump();

        return response()->json([
            'ok' => true,
            'message' => 'Imagem adicionada ao carrossel com sucesso.',
            'item' => [
                'id' => $item->id,
                'title' => $item->title,
                'subtitle' => $item->subtitle,
                'link_url' => $item->link_url,
                'button_text' => $item->button_text,
                'button_url' => $item->button_url,
                'image_url' => $this->imageUrl($item->image_path),
                'display_order' => $item->display_order,
                'is_active' => $item->is_active,
            ],
        ]);
    }

    public function carouselUpdate(Request $request, HomeCarouselItem $item): JsonResponse
    {
        $payload = $request->validate([
            'title' => ['nullable', 'string', 'max:100'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'url', 'max:2048', 'starts_with:http://,https://'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_url' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $before = $item->only(['title', 'subtitle', 'link_url', 'button_text', 'button_url', 'is_active']);

        $item->update([
            'title' => $payload['title'] ?? null,
            'subtitle' => $payload['subtitle'] ?? null,
            'link_url' => array_key_exists('link_url', $payload) ? ($payload['link_url'] ?? null) : $item->link_url,
            'button_text' => $payload['button_text'] ?? null,
            'button_url' => $payload['button_url'] ?? null,
            'is_active' => array_key_exists('is_active', $payload) ? (bool) $payload['is_active'] : $item->is_active,
        ]);

        $after = $item->only(['title', 'subtitle', 'link_url', 'button_text', 'button_url', 'is_active']);
        $changes = [];
        foreach ($after as $key => $newValue) {
            $oldValue = $before[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = ['old' => $oldValue, 'new' => $newValue];
            }
        }

        if ($changes !== []) {
            $this->audit($request, 'carousel', $item->id, 'updated', $changes);
            SiteCache::bump();
        }

        return response()->json(['ok' => true, 'message' => 'Item do carrossel atualizado com sucesso.']);
    }

    public function carouselDestroy(Request $request, HomeCarouselItem $item): JsonResponse
    {
        $this->audit($request, 'carousel', $item->id, 'deleted', [
            'image_path' => ['old' => $item->image_path, 'new' => null],
            'title' => ['old' => $item->title, 'new' => null],
            'subtitle' => ['old' => $item->subtitle, 'new' => null],
            'link_url' => ['old' => $item->link_url, 'new' => null],
            'button_text' => ['old' => $item->button_text, 'new' => null],
            'button_url' => ['old' => $item->button_url, 'new' => null],
        ]);

        $this->deleteImage($item->image_path);
        $item->delete();

        SiteCache::bump();

        return response()->json(['ok' => true, 'message' => 'Item do carrossel removido com sucesso.']);
    }

    public function carouselReorder(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $ids = array_values(array_unique($payload['ids']));
        $existing = HomeCarouselItem::query()->whereIn('id', $ids)->pluck('id')->all();
        sort($existing);
        $idsSorted = $ids;
        sort($idsSorted);
        if ($existing !== $idsSorted) {
            return response()->json(['ok' => false, 'message' => 'Não foi possível reordenar os itens do carrossel.'], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                HomeCarouselItem::query()->where('id', $id)->update(['display_order' => $index]);
            }
        });

        $this->audit($request, 'carousel', null, 'reordered', ['ids' => $ids]);
        SiteCache::bump();

        return response()->json(['ok' => true, 'message' => 'Carrossel reordenado com sucesso.']);
    }

    public function cardStore(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'detail_enabled' => ['nullable', 'boolean'],
            'detail_title' => ['nullable', 'string', 'max:140'],
            'detail_subtitle' => ['nullable', 'string', 'max:255'],
            'detail_body' => ['nullable', 'string'],
            'detail_image_caption' => ['nullable', 'string', 'max:160'],
            'detail_button_text' => ['nullable', 'string', 'max:80'],
            'icon' => ['nullable', 'string', 'max:60'],
            'link_url' => ['nullable', 'string', 'url', 'max:2048', 'starts_with:http://,https://'],
        ]);

        $nextOrder = (int) (HomeCard::query()->max('display_order') ?? 0) + 1;

        $card = HomeCard::query()->create([
            'title' => $payload['title'],
            'description' => $payload['description'] ?? null,
            'detail_enabled' => (bool) ($payload['detail_enabled'] ?? false),
            'detail_title' => $payload['detail_title'] ?? null,
            'detail_subtitle' => $payload['detail_subtitle'] ?? null,
            'detail_body' => $payload['detail_body'] ?? null,
            'detail_image_caption' => $payload['detail_image_caption'] ?? null,
            'detail_button_text' => $payload['detail_button_text'] ?? null,
            'icon' => $payload['icon'] ?? null,
            'link_url' => $payload['link_url'] ?? null,
            'display_order' => $nextOrder,
            'is_active' => true,
        ]);

        $this->audit($request, 'card', $card->id, 'created', [
            'title' => ['old' => null, 'new' => $card->title],
            'description' => ['old' => null, 'new' => $card->description],
            'detail_enabled' => ['old' => null, 'new' => $card->detail_enabled],
            'detail_title' => ['old' => null, 'new' => $card->detail_title],
            'detail_subtitle' => ['old' => null, 'new' => $card->detail_subtitle],
            'detail_body' => ['old' => null, 'new' => $card->detail_body],
            'detail_image_caption' => ['old' => null, 'new' => $card->detail_image_caption],
            'detail_button_text' => ['old' => null, 'new' => $card->detail_button_text],
            'icon' => ['old' => null, 'new' => $card->icon],
            'link_url' => ['old' => null, 'new' => $card->link_url],
            'display_order' => ['old' => null, 'new' => $card->display_order],
        ]);

        SiteCache::bump();

        return response()->json([
            'ok' => true,
            'message' => 'Card criado com sucesso.',
            'card' => [
                'id' => $card->id,
                'title' => $card->title,
                'description' => $card->description,
                'detail_enabled' => $card->detail_enabled,
                'detail_title' => $card->detail_title,
                'detail_subtitle' => $card->detail_subtitle,
                'detail_body' => $card->detail_body,
                'detail_image_path' => $card->detail_image_path,
                'detail_image_url' => $card->detail_image_path ? $this->imageUrl($card->detail_image_path) : null,
                'detail_image_caption' => $card->detail_image_caption,
                'detail_button_text' => $card->detail_button_text,
                'icon' => $card->icon,
                'link_url' => $card->link_url,
                'display_order' => $card->display_order,
                'is_active' => $card->is_active,
            ],
        ]);
    }

    public function cardUpdate(Request $request, HomeCard $card): JsonResponse
    {
        $payload = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'detail_enabled' => ['nullable', 'boolean'],
            'detail_title' => ['nullable', 'string', 'max:140'],
            'detail_subtitle' => ['nullable', 'string', 'max:255'],
            'detail_body' => ['nullable', 'string'],
            'detail_image_path' => ['nullable', 'string', 'max:2048'],
            'detail_image_caption' => ['nullable', 'string', 'max:160'],
            'detail_button_text' => ['nullable', 'string', 'max:80'],
            'icon' => ['nullable', 'string', 'max:60'],
            'link_url' => ['nullable', 'string', 'url', 'max:2048', 'starts_with:http://,https://'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $before = $card->only([
            'title',
            'description',
            'detail_enabled',
            'detail_title',
            'detail_subtitle',
            'detail_body',
            'detail_image_path',
            'detail_image_caption',
            'detail_button_text',
            'icon',
            'link_url',
            'is_active',
        ]);

        $card->update([
            'title' => $payload['title'],
            'description' => $payload['description'] ?? null,
            'detail_enabled' => array_key_exists('detail_enabled', $payload) ? (bool) $payload['detail_enabled'] : $card->detail_enabled,
            'detail_title' => $payload['detail_title'] ?? null,
            'detail_subtitle' => $payload['detail_subtitle'] ?? null,
            'detail_body' => $payload['detail_body'] ?? null,
            'detail_image_path' => $payload['detail_image_path'] ?? $card->detail_image_path,
            'detail_image_caption' => $payload['detail_image_caption'] ?? null,
            'detail_button_text' => $payload['detail_button_text'] ?? null,
            'icon' => $payload['icon'] ?? null,
            'link_url' => $payload['link_url'] ?? null,
            'is_active' => array_key_exists('is_active', $payload) ? (bool) $payload['is_active'] : $card->is_active,
        ]);

        $after = $card->only([
            'title',
            'description',
            'detail_enabled',
            'detail_title',
            'detail_subtitle',
            'detail_body',
            'detail_image_path',
            'detail_image_caption',
            'detail_button_text',
            'icon',
            'link_url',
            'is_active',
        ]);
        $changes = [];
        foreach ($after as $key => $newValue) {
            $oldValue = $before[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = ['old' => $oldValue, 'new' => $newValue];
            }
        }

        if ($changes !== []) {
            $this->audit($request, 'card', $card->id, 'updated', $changes);
            SiteCache::bump();
        }

        return response()->json(['ok' => true, 'message' => 'Card atualizado com sucesso.']);
    }

    public function cardDestroy(Request $request, HomeCard $card): JsonResponse
    {
        $this->audit($request, 'card', $card->id, 'deleted', [
            'title' => ['old' => $card->title, 'new' => null],
        ]);

        if (is_string($card->detail_image_path) && $card->detail_image_path !== '') {
            $this->deleteImage($card->detail_image_path);
        }
        $card->delete();
        SiteCache::bump();

        return response()->json(['ok' => true, 'message' => 'Card removido com sucesso.']);
    }

    public function cardDetailImageStore(Request $request, HomeCard $card): JsonResponse
    {
        $payload = $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        /** @var UploadedFile $file */
        $file = $payload['file'];
        $path = $this->storeOptimizedImage($file, 'imagens/cards');

        $oldPath = $card->detail_image_path;
        $card->update(['detail_image_path' => $path]);

        if (is_string($oldPath) && $oldPath !== '' && $oldPath !== $path) {
            $this->deleteImage($oldPath);
        }

        $this->audit($request, 'card', $card->id, 'updated', [
            'detail_image_path' => ['old' => $oldPath, 'new' => $path],
        ]);
        SiteCache::bump();

        return response()->json([
            'ok' => true,
            'message' => 'Imagem da descrição vinculada enviada com sucesso.',
            'image_path' => $path,
            'image_url' => $this->imageUrl($path),
        ]);
    }

    public function cardReorder(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $ids = array_values(array_unique($payload['ids']));
        $existing = HomeCard::query()->whereIn('id', $ids)->pluck('id')->all();
        sort($existing);
        $idsSorted = $ids;
        sort($idsSorted);
        if ($existing !== $idsSorted) {
            return response()->json(['ok' => false, 'message' => 'Não foi possível reordenar os cards.'], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                HomeCard::query()->where('id', $id)->update(['display_order' => $index]);
            }
        });

        $this->audit($request, 'card', null, 'reordered', ['ids' => $ids]);
        SiteCache::bump();

        return response()->json(['ok' => true, 'message' => 'Cards reordenados com sucesso.']);
    }

    private function storeOptimizedImage(UploadedFile $file, string $relativeDir): string
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
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

    private function storeFaviconAssets(UploadedFile $file): void
    {
        $dir = public_path('imagens');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $faviconPath = $dir.DIRECTORY_SEPARATOR.'favicon.png';
        $appleTouchPath = $dir.DIRECTORY_SEPARATOR.'apple-touch-icon.png';

        $tmpPath = $file->getRealPath();
        if (! is_string($tmpPath) || $tmpPath === '') {
            $file->move($dir, 'favicon.png');
            return;
        }

        $info = @getimagesize($tmpPath);
        $mime = is_array($info) && isset($info['mime']) ? (string) $info['mime'] : '';

        $create = null;
        if ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
            $create = fn () => @imagecreatefrompng($tmpPath);
        } elseif ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
            $create = fn () => @imagecreatefromjpeg($tmpPath);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $create = fn () => @imagecreatefromwebp($tmpPath);
        } elseif ($mime === 'image/gif' && function_exists('imagecreatefromgif')) {
            $create = fn () => @imagecreatefromgif($tmpPath);
        }

        if (! $create || ! function_exists('imagescale') || ! function_exists('imagepng')) {
            $file->move($dir, 'favicon.png');
            return;
        }

        $src = $create();
        if (! $src) {
            $file->move($dir, 'favicon.png');
            return;
        }

        $writePng = function ($image, string $target, int $size): void {
            $scaled = @imagescale($image, $size, $size);
            if (! $scaled) {
                return;
            }
            if (function_exists('imagealphablending')) {
                @imagealphablending($scaled, false);
            }
            if (function_exists('imagesavealpha')) {
                @imagesavealpha($scaled, true);
            }
            @imagepng($scaled, $target, 9);
            if ((is_resource($scaled) || (is_object($scaled) && get_class($scaled) === 'GdImage')) && $scaled !== $image) {
                @imagedestroy($scaled);
            }
        };

        $writePng($src, $faviconPath, 64);
        $writePng($src, $appleTouchPath, 180);

        if (is_resource($src) || (is_object($src) && get_class($src) === 'GdImage')) {
            @imagedestroy($src);
        }
    }

    private function imageUrl(string $path): string
    {
        if (str_starts_with($path, 'imagens/') || str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
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

    private function audit(Request $request, string $entityType, ?int $entityId, string $action, array $changes): void
    {
        HomeContentAudit::query()->create([
            'user_id' => $request->user()?->id,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'changes' => $changes,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    private function keys(): array
    {
        return [
            'company_name',
            'tagline',
            'phone',
            'phone2',
            'message',
            'email',
            'address',
            'about',
            'seo_title',
            'seo_description',
        ];
    }
}
