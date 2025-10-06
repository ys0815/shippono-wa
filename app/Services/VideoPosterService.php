<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoPosterService
{
    /**
     * 動画から静止画を抽出してposter属性用の画像を生成
     *
     * @param UploadedFile $videoFile
     * @param string $directory
     * @return string|null 生成された静止画のパス
     */
    public function generatePoster(UploadedFile $videoFile, string $directory = 'video-posters'): ?string
    {
        \Log::info('Starting video poster generation', [
            'original_name' => $videoFile->getClientOriginalName(),
            'file_size' => $videoFile->getSize(),
            'mime_type' => $videoFile->getMimeType(),
            'directory' => $directory
        ]);

        try {
            // 一時ファイルとして動画を保存
            $tempVideoPath = $videoFile->store('temp', 'local');
            $fullTempPath = storage_path('app/' . $tempVideoPath);

            \Log::info('Video file saved to temp location', [
                'temp_path' => $tempVideoPath,
                'full_temp_path' => $fullTempPath,
                'file_exists' => file_exists($fullTempPath)
            ]);

            // 静止画のファイル名を生成
            $posterFilename = 'poster_' . Str::random(10) . '.jpg';
            $posterPath = $directory . '/' . $posterFilename;
            $fullPosterPath = storage_path('app/public/' . $posterPath);

            \Log::info('Poster paths prepared', [
                'filename' => $posterFilename,
                'relative_path' => $posterPath,
                'full_path' => $fullPosterPath
            ]);

            // ディレクトリが存在しない場合は作成
            $posterDir = dirname($fullPosterPath);
            if (!is_dir($posterDir)) {
                mkdir($posterDir, 0755, true);
                \Log::info('Created poster directory', ['directory' => $posterDir]);
            }

            // FFmpegコマンドで静止画を抽出（1秒目のフレーム）
            $command = sprintf(
                'ffmpeg -i %s -ss 1 -vframes 1 -q:v 2 %s 2>&1',
                escapeshellarg($fullTempPath),
                escapeshellarg($fullPosterPath)
            );

            \Log::info('Executing FFmpeg command', ['command' => $command]);

            $output = shell_exec($command);
            $exitCode = $this->getLastExitCode();

            \Log::info('FFmpeg command completed', [
                'exit_code' => $exitCode,
                'output' => $output
            ]);

            // 一時ファイルを削除
            Storage::disk('local')->delete($tempVideoPath);
            \Log::info('Temp file cleaned up');

            // 静止画が正常に生成されたかチェック
            if (file_exists($fullPosterPath) && filesize($fullPosterPath) > 0) {
                \Log::info('Video poster generated successfully', [
                    'video_path' => $videoFile->getClientOriginalName(),
                    'poster_path' => $posterPath,
                    'poster_size' => filesize($fullPosterPath)
                ]);
                return $posterPath;
            } else {
                \Log::error('Poster file was not created or is empty', [
                    'poster_exists' => file_exists($fullPosterPath),
                    'poster_size' => file_exists($fullPosterPath) ? filesize($fullPosterPath) : 'N/A'
                ]);
                return null;
            }
        } catch (\Exception $e) {
            \Log::error('Video poster generation failed', [
                'video_file' => $videoFile->getClientOriginalName(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);

            // 一時ファイルを削除
            if (isset($tempVideoPath)) {
                Storage::disk('local')->delete($tempVideoPath);
                \Log::info('Temp file cleaned up after error');
            }

            return null;
        }
    }

    /**
     * 既存の動画ファイルから静止画を生成
     *
     * @param string $videoPath
     * @param string $directory
     * @return string|null
     */
    public function generatePosterFromPath(string $videoPath, string $directory = 'video-posters'): ?string
    {
        try {
            $fullVideoPath = storage_path('app/public/' . $videoPath);

            if (!file_exists($fullVideoPath)) {
                \Log::warning('Video file not found for poster generation', ['path' => $fullVideoPath]);
                return null;
            }

            // 静止画のファイル名を生成
            $posterFilename = 'poster_' . Str::random(10) . '.jpg';
            $posterPath = $directory . '/' . $posterFilename;
            $fullPosterPath = storage_path('app/public/' . $posterPath);

            // ディレクトリが存在しない場合は作成
            $posterDir = dirname($fullPosterPath);
            if (!is_dir($posterDir)) {
                mkdir($posterDir, 0755, true);
            }

            // FFmpegコマンドで静止画を抽出
            $command = sprintf(
                'ffmpeg -i %s -ss 1 -vframes 1 -q:v 2 %s 2>&1',
                escapeshellarg($fullVideoPath),
                escapeshellarg($fullPosterPath)
            );

            \Log::info('Executing FFmpeg command for existing video', ['command' => $command]);

            $output = shell_exec($command);
            $exitCode = $this->getLastExitCode();

            if (file_exists($fullPosterPath) && filesize($fullPosterPath) > 0) {
                \Log::info('Video poster generated from existing file', [
                    'video_path' => $videoPath,
                    'poster_path' => $posterPath
                ]);
                return $posterPath;
            } else {
                \Log::error('Poster generation failed for existing video', [
                    'video_path' => $videoPath,
                    'exit_code' => $exitCode,
                    'output' => $output
                ]);
                return null;
            }
        } catch (\Exception $e) {
            \Log::error('Video poster generation from path failed: ' . $e->getMessage(), [
                'video_path' => $videoPath,
                'error' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * 最後のコマンドの終了コードを取得
     */
    private function getLastExitCode(): int
    {
        $output = shell_exec('echo $?');
        return (int) trim($output);
    }
}
