<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\ProjectTempMedia;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('projects:cleanup-temp-media', function () {
    $cutoff = now()->subDays(3);

    $items = ProjectTempMedia::query()
        ->where('created_at', '<', $cutoff)
        ->orderBy('id')
        ->get();

    $deleted = 0;

    foreach ($items as $item) {
        $path = is_string($item->image_path) ? trim($item->image_path) : '';
        if ($path !== '' && (str_starts_with($path, 'imagens/') || str_starts_with($path, 'images/'))) {
            $fullPath = public_path($path);
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }

            $dir = dirname($fullPath);
            if (is_dir($dir)) {
                $leftovers = @scandir($dir);
                if (is_array($leftovers) && count(array_diff($leftovers, ['.', '..'])) === 0) {
                    @rmdir($dir);
                }
            }
        }

        $item->delete();
        $deleted++;
    }

    $this->info("Itens removidos: {$deleted}");
})->purpose('Remove mídias temporárias de projetos com mais de 3 dias')->daily();
