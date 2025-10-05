<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizationService;
use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {--force : Force optimization even if optimized images exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize existing images in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image optimization...');

        $imageService = new ImageOptimizationService();
        $force = $this->option('force');

        // 投稿のメディア画像を最適化
        $this->optimizePostImages($imageService, $force);

        // ペットのプロフィール画像を最適化
        $this->optimizePetImages($imageService, $force);

        $this->info('Image optimization completed!');
    }

    /**
     * 投稿のメディア画像を最適化
     */
    private function optimizePostImages(ImageOptimizationService $imageService, bool $force)
    {
        $this->info('Optimizing post images...');

        $mediaFiles = Media::where('type', 'image')->get();
        $bar = $this->output->createProgressBar($mediaFiles->count());

        foreach ($mediaFiles as $media) {
            $this->optimizeImage($imageService, $media->url, 'posts', $force);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * ペットのプロフィール画像を最適化
     */
    private function optimizePetImages(ImageOptimizationService $imageService, bool $force)
    {
        $this->info('Optimizing pet profile images...');

        // ペットのプロフィール画像とヘッダー画像を取得
        $pets = \App\Models\Pet::whereNotNull('profile_image_url')
            ->orWhereNotNull('header_image_url')
            ->get();

        $bar = $this->output->createProgressBar($pets->count() * 2);

        foreach ($pets as $pet) {
            if ($pet->profile_image_url && str_contains($pet->profile_image_url, '/storage/')) {
                $path = str_replace('/storage/', '', $pet->profile_image_url);
                $this->optimizeImage($imageService, $path, 'pets/profile', $force);
            }
            $bar->advance();

            if ($pet->header_image_url && str_contains($pet->header_image_url, '/storage/')) {
                $path = str_replace('/storage/', '', $pet->header_image_url);
                $this->optimizeImage($imageService, $path, 'pets/header', $force);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * 個別の画像を最適化
     */
    private function optimizeImage(ImageOptimizationService $imageService, string $path, string $directory, bool $force)
    {
        try {
            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                $this->warn("File not found: {$path}");
                return;
            }

            // ファイルサイズをチェック（1MB以上の場合のみ最適化）
            $fileSize = filesize($fullPath);
            if ($fileSize < 1024 * 1024) {
                $this->line("Skipping small file: {$path} ({$this->formatBytes($fileSize)})");
                return;
            }

            // 既に最適化済みかチェック
            if (!$force && $this->isAlreadyOptimized($path)) {
                $this->line("Already optimized: {$path}");
                return;
            }

            // メモリ制限を一時的に増やす
            ini_set('memory_limit', '512M');

            // 最適化実行
            $optimizedImages = $imageService->optimizeExistingImage($path, $directory);

            if (!empty($optimizedImages)) {
                $this->line("Optimized: {$path} -> " . count($optimizedImages) . " sizes");
            } else {
                $this->warn("Failed to optimize: {$path}");
            }

            // メモリを強制的に解放
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        } catch (\Exception $e) {
            $this->error("Error optimizing {$path}: " . $e->getMessage());
        }
    }

    /**
     * 既に最適化済みかチェック
     */
    private function isAlreadyOptimized(string $path): bool
    {
        // ファイル名に最適化のマーカーが含まれているかチェック
        return str_contains($path, '_large_') ||
            str_contains($path, '_medium_') ||
            str_contains($path, '_thumbnail_');
    }

    /**
     * バイト数を読みやすい形式に変換
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
