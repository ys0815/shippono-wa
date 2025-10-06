<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoThumbnailService
{
    private FFMpeg $ffmpeg;

    public function __construct()
    {
        $this->ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout'          => 3600,
            'ffmpeg.threads'   => 12,
        ]);
    }

    /**
     * 動画からサムネイル画像を生成
     *
     * @param UploadedFile $videoFile
     * @param string $directory
     * @return string|null 生成されたサムネイル画像のパス
     */
    public function generateThumbnail(UploadedFile $videoFile, string $directory = 'video-thumbnails'): ?string
    {
        try {
            // 一時ファイルとして動画を保存
            $tempVideoPath = $videoFile->store('temp', 'local');
            $fullTempPath = storage_path('app/' . $tempVideoPath);

            // 動画ファイルを開く
            $video = $this->ffmpeg->open($fullTempPath);

            // 動画の長さを取得
            $duration = $video->getStreams()->videos()->first()->get('duration');

            // サムネイルを取得する時間（動画の10%の位置、最低1秒、最大30秒）
            $thumbnailTime = max(1, min(30, $duration * 0.1));

            // サムネイル画像のファイル名を生成
            $thumbnailFilename = 'thumb_' . Str::random(10) . '.jpg';
            $thumbnailPath = $directory . '/' . $thumbnailFilename;
            $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);

            // サムネイル画像を生成
            $video
                ->frame(TimeCode::fromSeconds($thumbnailTime))
                ->save($fullThumbnailPath);

            // 一時ファイルを削除
            Storage::disk('local')->delete($tempVideoPath);

            \Log::info('Video thumbnail generated successfully', [
                'video_path' => $videoFile->getClientOriginalName(),
                'thumbnail_path' => $thumbnailPath,
                'thumbnail_time' => $thumbnailTime
            ]);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Video thumbnail generation failed: ' . $e->getMessage(), [
                'video_file' => $videoFile->getClientOriginalName(),
                'error' => $e->getTraceAsString()
            ]);

            // 一時ファイルを削除
            if (isset($tempVideoPath)) {
                Storage::disk('local')->delete($tempVideoPath);
            }

            return null;
        }
    }

    /**
     * 既存の動画ファイルからサムネイルを生成
     *
     * @param string $videoPath
     * @param string $directory
     * @return string|null
     */
    public function generateThumbnailFromPath(string $videoPath, string $directory = 'video-thumbnails'): ?string
    {
        try {
            $fullVideoPath = storage_path('app/public/' . $videoPath);

            if (!file_exists($fullVideoPath)) {
                \Log::warning('Video file not found for thumbnail generation', ['path' => $fullVideoPath]);
                return null;
            }

            // 動画ファイルを開く
            $video = $this->ffmpeg->open($fullVideoPath);

            // 動画の長さを取得
            $duration = $video->getStreams()->videos()->first()->get('duration');

            // サムネイルを取得する時間（動画の10%の位置、最低1秒、最大30秒）
            $thumbnailTime = max(1, min(30, $duration * 0.1));

            // サムネイル画像のファイル名を生成
            $thumbnailFilename = 'thumb_' . Str::random(10) . '.jpg';
            $thumbnailPath = $directory . '/' . $thumbnailFilename;
            $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);

            // サムネイル画像を生成
            $video
                ->frame(TimeCode::fromSeconds($thumbnailTime))
                ->save($fullThumbnailPath);

            \Log::info('Video thumbnail generated from existing file', [
                'video_path' => $videoPath,
                'thumbnail_path' => $thumbnailPath,
                'thumbnail_time' => $thumbnailTime
            ]);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Video thumbnail generation from path failed: ' . $e->getMessage(), [
                'video_path' => $videoPath,
                'error' => $e->getTraceAsString()
            ]);

            return null;
        }
    }
}
