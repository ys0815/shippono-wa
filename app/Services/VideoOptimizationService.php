<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class VideoOptimizationService
{
    /**
     * 動画を最適化して保存
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $configs
     * @return array 保存されたファイルパスの配列
     */
    public function optimizeAndSave(UploadedFile $file, string $directory = 'posts', array $configs = []): array
    {
        $savedPaths = [];

        // FFmpegが利用可能かチェック
        $ffmpegPath = $this->getFFmpegPath();
        if (!$ffmpegPath) {
            Log::warning('FFmpeg not available, skipping video optimization');
            return $savedPaths; // 空の配列を返す
        }

        // デフォルト設定
        $defaultConfigs = [
            'thumbnail' => [
                'width' => 480,
                'height' => 360,
                'bitrate' => '500k',
                'quality' => 23, // CRF値（0-51、低いほど高品質）
            ],
            'medium' => [
                'width' => 1280,
                'height' => 720,
                'bitrate' => '1500k',
                'quality' => 23,
            ],
            'large' => [
                'width' => 1920,
                'height' => 1080,
                'bitrate' => '3000k',
                'quality' => 20,
            ],
        ];

        $configs = array_merge($defaultConfigs, $configs);

        foreach ($configs as $sizeName => $config) {
            $path = $this->createOptimizedVideo($file, $directory, $sizeName, $config);
            if ($path) {
                $savedPaths[$sizeName] = $path;
            }
        }

        return $savedPaths;
    }

    /**
     * 単一サイズの最適化動画を作成
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $sizeName
     * @param array $config
     * @return string|null
     */
    private function createOptimizedVideo(UploadedFile $file, string $directory, string $sizeName, array $config): ?string
    {
        try {
            // FFmpegのパスを取得
            $ffmpegPath = $this->getFFmpegPath();
            if (!$ffmpegPath) {
                Log::error('FFmpeg not found');
                return null;
            }

            // 元動画の情報を取得
            $videoInfo = $this->getVideoInfo($file->getPathname(), $ffmpegPath);
            if (!$videoInfo) {
                Log::error('Failed to get video info');
                return null;
            }

            Log::info("Original video info: {$videoInfo['width']}x{$videoInfo['height']}, duration: {$videoInfo['duration']}s");

            // アスペクト比を保持してリサイズ設定を計算
            $resizeConfig = $this->calculateResizeConfig($videoInfo, $config);

            // ファイル名を生成
            $filename = $this->generateFilename($file, $sizeName);
            $outputPath = storage_path('app/public/' . $directory . '/' . $filename);

            // 出力ディレクトリを作成
            $outputDir = dirname($outputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // FFmpegコマンドを構築
            $command = $this->buildFFmpegCommand(
                $ffmpegPath,
                $file->getPathname(),
                $outputPath,
                $resizeConfig,
                $config
            );

            Log::info('FFmpeg command: ' . $command);

            // 動画を処理
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5分のタイムアウト
            $process->run();

            if ($process->isSuccessful()) {
                Log::info("Video optimization successful: {$sizeName}");
                return $directory . '/' . $filename;
            } else {
                Log::error('FFmpeg process failed: ' . $process->getErrorOutput());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Video optimization failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * 動画の情報を取得
     *
     * @param string $videoPath
     * @param string $ffmpegPath
     * @return array|null
     */
    private function getVideoInfo(string $videoPath, string $ffmpegPath): ?array
    {
        try {
            $command = "{$ffmpegPath} -i " . escapeshellarg($videoPath) . " -f null - 2>&1";
            $process = Process::fromShellCommandline($command);
            $process->run();

            $output = $process->getErrorOutput();

            // 解像度を抽出
            if (preg_match('/(\d+)x(\d+)/', $output, $matches)) {
                $width = (int)$matches[1];
                $height = (int)$matches[2];
            } else {
                return null;
            }

            // 動画の長さを抽出
            $duration = 0;
            if (preg_match('/Duration: (\d+):(\d+):(\d+\.?\d*)/', $output, $matches)) {
                $hours = (int)$matches[1];
                $minutes = (int)$matches[2];
                $seconds = (float)$matches[3];
                $duration = $hours * 3600 + $minutes * 60 + $seconds;
            }

            return [
                'width' => $width,
                'height' => $height,
                'duration' => $duration,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get video info: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * リサイズ設定を計算
     *
     * @param array $videoInfo
     * @param array $config
     * @return array
     */
    private function calculateResizeConfig(array $videoInfo, array $config): array
    {
        $originalWidth = $videoInfo['width'];
        $originalHeight = $videoInfo['height'];
        $aspectRatio = $originalHeight / $originalWidth;

        $targetWidth = $config['width'];
        $targetHeight = $config['height'];

        // アスペクト比を保持してリサイズ
        if ($originalWidth > $originalHeight) {
            // 横長動画
            $newWidth = min($targetWidth, $originalWidth);
            $newHeight = (int)round($newWidth * $aspectRatio);
        } else {
            // 縦長動画
            $newHeight = min($targetHeight, $originalHeight);
            $newWidth = (int)round($newHeight / $aspectRatio);
        }

        // 偶数に調整（FFmpegの要件）
        $newWidth = $newWidth % 2 === 0 ? $newWidth : $newWidth - 1;
        $newHeight = $newHeight % 2 === 0 ? $newHeight : $newHeight - 1;

        return [
            'width' => $newWidth,
            'height' => $newHeight,
        ];
    }

    /**
     * FFmpegコマンドを構築
     *
     * @param string $ffmpegPath
     * @param string $inputPath
     * @param string $outputPath
     * @param array $resizeConfig
     * @param array $config
     * @return string
     */
    private function buildFFmpegCommand(string $ffmpegPath, string $inputPath, string $outputPath, array $resizeConfig, array $config): string
    {
        $width = $resizeConfig['width'];
        $height = $resizeConfig['height'];
        $bitrate = $config['bitrate'] ?? '1500k';
        $quality = $config['quality'] ?? 23;

        return sprintf(
            '%s -i %s -vf "scale=%d:%d" -c:v libx264 -preset medium -crf %d -b:v %s -c:a aac -b:a 128k -movflags +faststart %s',
            $ffmpegPath,
            escapeshellarg($inputPath),
            $width,
            $height,
            $quality,
            $bitrate,
            escapeshellarg($outputPath)
        );
    }

    /**
     * FFmpegのパスを取得
     *
     * @return string|null
     */
    private function getFFmpegPath(): ?string
    {
        $possiblePaths = [
            'ffmpeg',
            '/usr/bin/ffmpeg',
            '/usr/local/bin/ffmpeg',
            '/opt/homebrew/bin/ffmpeg',
        ];

        foreach ($possiblePaths as $path) {
            $process = Process::fromShellCommandline("which {$path}");
            $process->run();

            if ($process->isSuccessful()) {
                return $path;
            }
        }

        return null;
    }

    /**
     * ファイル名を生成
     *
     * @param UploadedFile $file
     * @param string $sizeName
     * @return string
     */
    private function generateFilename(UploadedFile $file, string $sizeName): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $hash = Str::random(10);

        return Str::slug($originalName) . '_' . $sizeName . '_' . $hash . '.mp4';
    }

    /**
     * 動画のサムネイルを生成
     *
     * @param string $videoPath
     * @param string $directory
     * @param int $timeOffset
     * @return string|null
     */
    public function generateThumbnail(string $videoPath, string $directory = 'posts', int $timeOffset = 1): ?string
    {
        try {
            $ffmpegPath = $this->getFFmpegPath();
            if (!$ffmpegPath) {
                return null;
            }

            $filename = pathinfo($videoPath, PATHINFO_FILENAME) . '_thumb_' . Str::random(10) . '.jpg';
            $outputPath = storage_path('app/public/' . $directory . '/' . $filename);

            $command = sprintf(
                '%s -i %s -ss %d -vframes 1 -vf "scale=480:360" %s',
                $ffmpegPath,
                escapeshellarg($videoPath),
                $timeOffset,
                escapeshellarg($outputPath)
            );

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(30);
            $process->run();

            if ($process->isSuccessful()) {
                return $directory . '/' . $filename;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
