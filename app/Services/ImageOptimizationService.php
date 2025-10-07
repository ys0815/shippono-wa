<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * 画像を最適化して保存
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $sizes
     * @return array 保存されたファイルパスの配列
     */
    public function optimizeAndSave(UploadedFile $file, string $directory = 'posts', array $sizes = []): array
    {
        try {
            $savedPaths = [];

            // iOSのSafariでよく発生する問題に対応
            if (!$file->isValid()) {
                Log::error('Invalid file upload', [
                    'error' => $file->getError(),
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
                return [];
            }

            // デフォルトサイズ設定
            // 高さは指定せず、幅基準でアスペクト比を維持
            $defaultSizes = [
                'thumbnail' => ['width' => 300, 'quality' => 80],
                'medium' => ['width' => 800, 'quality' => 85],
                'large' => ['width' => 1200, 'quality' => 90],
            ];

            $sizes = array_merge($defaultSizes, $sizes);

            foreach ($sizes as $sizeName => $config) {
                try {
                    $path = $this->createOptimizedImage($file, $directory, $sizeName, $config);
                    if ($path) {
                        $savedPaths[$sizeName] = $path;
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to create optimized image', [
                        'size' => $sizeName,
                        'error' => $e->getMessage(),
                        'filename' => $file->getClientOriginalName()
                    ]);
                    // 個別のサイズでエラーが発生しても処理を継続
                }

                // メモリを解放
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }

            return $savedPaths;
        } catch (\Exception $e) {
            Log::error('ImageOptimizationService::optimizeAndSave failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filename' => $file->getClientOriginalName(),
                'directory' => $directory
            ]);
            return [];
        }
    }

    /**
     * 単一サイズの最適化画像を作成
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $sizeName
     * @param array $config
     * @return string|null
     */
    private function createOptimizedImage(UploadedFile $file, string $directory, string $sizeName, array $config): ?string
    {
        try {
            // iOSのSafariでよく発生する問題に対応
            $filePath = $file->getPathname();
            if (!file_exists($filePath)) {
                Log::error('Image file not found', ['path' => $filePath]);
                return null;
            }

            if (!is_readable($filePath)) {
                Log::error('Image file not readable', ['path' => $filePath]);
                return null;
            }

            $image = $this->imageManager->read($filePath);

            // iOSのライブラリから選択した画像の詳細情報をログに記録
            Log::info('Processing image from iOS library', [
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'dimensions' => $image->width() . 'x' . $image->height(),
                'user_agent' => request()->userAgent(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true)
            ]);

            // メモリ使用量が多すぎる場合は警告
            if (memory_get_usage(true) > 128 * 1024 * 1024) { // 128MB
                Log::warning('High memory usage detected during image processing', [
                    'memory_usage' => memory_get_usage(true),
                    'filename' => $file->getClientOriginalName()
                ]);
            }

            // EXIFの向きを確実に補正
            try {
                // 新しいIntervention ImageのAPIを使用
                $image->orient();
                Log::info('EXIF orientation corrected using orient()');
            } catch (\Throwable $t) {
                Log::warning('EXIF orientation correction failed with orient(): ' . $t->getMessage());
                // 手動でEXIF向きを確認・補正
                try {
                    $this->manualOrientationCorrection($image, $file);
                    Log::info('Manual orientation correction applied');
                } catch (\Throwable $t2) {
                    Log::warning('Manual orientation correction also failed: ' . $t2->getMessage());
                    // 向き補正に失敗しても処理を継続（画像は表示されるが向きが間違っている可能性）
                }
            }

            // アスペクト比を保持してリサイズ（正確な計算）
            $targetWidth = $config['width'] ?? null;
            if ($targetWidth) {
                // 現在の画像サイズを取得
                $currentWidth = $image->width();
                $currentHeight = $image->height();
                $aspectRatio = $currentHeight / $currentWidth;

                Log::info("Image dimensions before resize: {$currentWidth}x{$currentHeight}, aspect ratio: {$aspectRatio}");

                // 新しい高さを計算
                $newHeight = (int) round($targetWidth * $aspectRatio);

                Log::info("Calculated new dimensions: {$targetWidth}x{$newHeight}");

                // 正確なサイズでリサイズ
                $image->resize($targetWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // 元画像より大きくしない
                });

                Log::info("Final image dimensions: {$image->width()}x{$image->height()}");
            }

            // ファイル名を生成
            $filename = $this->generateFilename($file, $sizeName);
            $path = $directory . '/' . $filename;

            // 品質を設定して保存
            $image->toJpeg($config['quality'])->save(storage_path('app/public/' . $path));

            return $path;
        } catch (\Exception $e) {
            Log::error('Image optimization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filename' => $file->getClientOriginalName(),
                'size' => $sizeName,
                'memory_usage' => memory_get_usage(true),
                'user_agent' => request()->userAgent()
            ]);
            return null;
        }
    }

    /**
     * WebP形式で画像を最適化して保存
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $sizes
     * @return array
     */
    public function optimizeAndSaveWebP(UploadedFile $file, string $directory = 'posts', array $sizes = []): array
    {
        $savedPaths = [];

        // 高さは指定せず、幅基準でアスペクト比を維持
        $defaultSizes = [
            'thumbnail' => ['width' => 300, 'quality' => 80],
            'medium' => ['width' => 800, 'quality' => 85],
            'large' => ['width' => 1200, 'quality' => 90],
        ];

        $sizes = array_merge($defaultSizes, $sizes);

        foreach ($sizes as $sizeName => $config) {
            $path = $this->createWebPImage($file, $directory, $sizeName, $config);
            if ($path) {
                $savedPaths[$sizeName] = $path;
            }
        }

        return $savedPaths;
    }

    /**
     * WebP形式の最適化画像を作成
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $sizeName
     * @param array $config
     * @return string|null
     */
    private function createWebPImage(UploadedFile $file, string $directory, string $sizeName, array $config): ?string
    {
        try {
            $image = $this->imageManager->read($file->getPathname());

            // EXIFの向きを確実に補正
            try {
                // 新しいIntervention ImageのAPIを使用
                $image->orient();
                Log::info('EXIF orientation corrected using orientate()');
            } catch (\Throwable $t) {
                // 古いAPIも試す
                try {
                    $image->orient();
                    Log::info('EXIF orientation corrected using orient()');
                } catch (\Throwable $t2) {
                    Log::warning('EXIF orientation correction failed: ' . $t2->getMessage());
                    // 手動でEXIF向きを確認・補正
                    $this->manualOrientationCorrection($image, $file);
                }
            }

            // アスペクト比を保持してリサイズ（正確な計算）
            $targetWidth = $config['width'] ?? null;
            if ($targetWidth) {
                // 現在の画像サイズを取得
                $currentWidth = $image->width();
                $currentHeight = $image->height();
                $aspectRatio = $currentHeight / $currentWidth;

                Log::info("WebP image dimensions before resize: {$currentWidth}x{$currentHeight}, aspect ratio: {$aspectRatio}");

                // 新しい高さを計算
                $newHeight = (int) round($targetWidth * $aspectRatio);

                Log::info("WebP calculated new dimensions: {$targetWidth}x{$newHeight}");

                // 正確なサイズでリサイズ
                $image->resize($targetWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Log::info("WebP final image dimensions: {$image->width()}x{$image->height()}");
            }

            $filename = $this->generateFilename($file, $sizeName, 'webp');
            $path = $directory . '/' . $filename;

            $image->toWebp($config['quality'])->save(storage_path('app/public/' . $path));

            return $path;
        } catch (\Exception $e) {
            Log::error('WebP optimization failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * ファイル名を生成
     *
     * @param UploadedFile $file
     * @param string $sizeName
     * @param string $extension
     * @return string
     */
    private function generateFilename(UploadedFile $file, string $sizeName, string $extension = 'jpg'): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $hash = Str::random(10);

        return Str::slug($originalName) . '_' . $sizeName . '_' . $hash . '.' . $extension;
    }

    /**
     * 既存の画像を最適化
     *
     * @param string $originalPath
     * @param string $directory
     * @param array $sizes
     * @return array
     */
    public function optimizeExistingImage(string $originalPath, string $directory = 'posts', array $sizes = []): array
    {
        $savedPaths = [];

        // 高さは指定せず、幅基準でアスペクト比を維持
        $defaultSizes = [
            'thumbnail' => ['width' => 300, 'quality' => 80],
            'medium' => ['width' => 800, 'quality' => 85],
            'large' => ['width' => 1200, 'quality' => 90],
        ];

        $sizes = array_merge($defaultSizes, $sizes);

        $fullPath = storage_path('app/public/' . $originalPath);

        if (!file_exists($fullPath)) {
            return $savedPaths;
        }

        foreach ($sizes as $sizeName => $config) {
            try {
                $image = $this->imageManager->read($fullPath);

                // EXIFの向きを確実に補正
                try {
                    // 新しいIntervention ImageのAPIを使用
                    $image->orient();
                } catch (\Throwable $t) {
                    // 古いAPIも試す
                    try {
                        $image->orient();
                    } catch (\Throwable $t2) {
                        Log::warning('EXIF orientation correction failed: ' . $t2->getMessage());
                    }
                }

                // アスペクト比を保持してリサイズ（正確な計算）
                $targetWidth = $config['width'] ?? null;
                if ($targetWidth) {
                    // 現在の画像サイズを取得
                    $currentWidth = $image->width();
                    $currentHeight = $image->height();
                    $aspectRatio = $currentHeight / $currentWidth;

                    Log::info("Existing image dimensions before resize: {$currentWidth}x{$currentHeight}, aspect ratio: {$aspectRatio}");

                    // 新しい高さを計算
                    $newHeight = (int) round($targetWidth * $aspectRatio);

                    Log::info("Existing image calculated new dimensions: {$targetWidth}x{$newHeight}");

                    // 正確なサイズでリサイズ
                    $image->resize($targetWidth, $newHeight, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    Log::info("Existing image final dimensions: {$image->width()}x{$image->height()}");
                }

                $filename = $this->generateFilenameFromPath($originalPath, $sizeName);
                $path = $directory . '/' . $filename;

                $image->toJpeg($config['quality'])->save(storage_path('app/public/' . $path));
                $savedPaths[$sizeName] = $path;

                // メモリを解放
                unset($image);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            } catch (\Exception $e) {
                Log::error('Existing image optimization failed: ' . $e->getMessage());
            }
        }

        return $savedPaths;
    }

    /**
     * パスからファイル名を生成
     *
     * @param string $path
     * @param string $sizeName
     * @param string $extension
     * @return string
     */
    private function generateFilenameFromPath(string $path, string $sizeName, string $extension = 'jpg'): string
    {
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $hash = Str::random(10);

        return $filename . '_' . $sizeName . '_' . $hash . '.' . $extension;
    }

    /**
     * 画像のメタデータを取得
     *
     * @param string $path
     * @return array
     */
    public function getImageMetadata(string $path): array
    {
        try {
            $fullPath = storage_path('app/public/' . $path);
            $image = $this->imageManager->read($fullPath);

            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => filesize($fullPath),
                'mime_type' => mime_content_type($fullPath),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get image metadata: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 手動でEXIF向きを確認・補正
     *
     * @param mixed $image
     * @param UploadedFile $file
     * @return void
     */
    private function manualOrientationCorrection($image, UploadedFile $file): void
    {
        try {
            // EXIFデータを直接読み取り
            $exif = @exif_read_data($file->getPathname());

            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                Log::info('Manual EXIF orientation correction: ' . $orientation);

                switch ($orientation) {
                    case 3:
                        $image->rotate(180);
                        break;
                    case 6:
                        $image->rotate(90);
                        break;
                    case 8:
                        $image->rotate(270);
                        break;
                    case 2:
                        $image->flip('h');
                        break;
                    case 4:
                        $image->flip('v');
                        break;
                    case 5:
                        $image->rotate(90)->flip('h');
                        break;
                    case 7:
                        $image->rotate(270)->flip('h');
                        break;
                }

                Log::info('Manual orientation correction applied successfully');
            } else {
                Log::info('No EXIF orientation data found, skipping manual correction');
            }
        } catch (\Throwable $t) {
            Log::warning('Manual orientation correction failed: ' . $t->getMessage());
        }
    }
}
