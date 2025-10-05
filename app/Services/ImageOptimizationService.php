<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $savedPaths = [];

        // デフォルトサイズ設定
        $defaultSizes = [
            'thumbnail' => ['width' => 300, 'height' => 300, 'quality' => 80],
            'medium' => ['width' => 800, 'height' => 600, 'quality' => 85],
            'large' => ['width' => 1200, 'height' => 900, 'quality' => 90],
        ];

        $sizes = array_merge($defaultSizes, $sizes);

        foreach ($sizes as $sizeName => $config) {
            $path = $this->createOptimizedImage($file, $directory, $sizeName, $config);
            if ($path) {
                $savedPaths[$sizeName] = $path;
            }

            // メモリを解放
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        }

        return $savedPaths;
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
            $image = $this->imageManager->read($file->getPathname());

            // アスペクト比を保持してリサイズ
            $image->resize($config['width'], $config['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // 元画像より大きくしない
            });

            // ファイル名を生成
            $filename = $this->generateFilename($file, $sizeName);
            $path = $directory . '/' . $filename;

            // 品質を設定して保存
            $image->toJpeg($config['quality'])->save(storage_path('app/public/' . $path));

            return $path;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
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

        $defaultSizes = [
            'thumbnail' => ['width' => 300, 'height' => 300, 'quality' => 80],
            'medium' => ['width' => 800, 'height' => 600, 'quality' => 85],
            'large' => ['width' => 1200, 'height' => 900, 'quality' => 90],
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

            $image->resize($config['width'], $config['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $filename = $this->generateFilename($file, $sizeName, 'webp');
            $path = $directory . '/' . $filename;

            $image->toWebp($config['quality'])->save(storage_path('app/public/' . $path));

            return $path;
        } catch (\Exception $e) {
            \Log::error('WebP optimization failed: ' . $e->getMessage());
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

        $defaultSizes = [
            'thumbnail' => ['width' => 300, 'height' => 300, 'quality' => 80],
            'medium' => ['width' => 800, 'height' => 600, 'quality' => 85],
            'large' => ['width' => 1200, 'height' => 900, 'quality' => 90],
        ];

        $sizes = array_merge($defaultSizes, $sizes);

        $fullPath = storage_path('app/public/' . $originalPath);

        if (!file_exists($fullPath)) {
            return $savedPaths;
        }

        foreach ($sizes as $sizeName => $config) {
            try {
                $image = $this->imageManager->read($fullPath);

                $image->resize($config['width'], $config['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

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
                \Log::error('Existing image optimization failed: ' . $e->getMessage());
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
            \Log::error('Failed to get image metadata: ' . $e->getMessage());
            return [];
        }
    }
}
