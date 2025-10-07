<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use App\Services\VideoOptimizationService;
use Illuminate\Support\Facades\Storage;

class BackfillVideoThumbnails extends Command
{
    protected $signature = 'media:backfill-video-thumbnails {--limit=100 : Max records to process in one run}';

    protected $description = 'Generate and store thumbnail_url for existing video media records missing one';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $this->info("Scanning up to {$limit} video media without thumbnail_url...");

        $query = Media::query()
            ->where('type', 'video')
            ->whereNull('thumbnail_url');

        $count = $query->count();
        if ($count === 0) {
            $this->info('No videos found without thumbnail_url.');
            return self::SUCCESS;
        }

        $this->info("Found {$count} candidates. Processing...");

        $videoService = new VideoOptimizationService();

        $processed = 0;
        $query->limit($limit)->get()->each(function (Media $media) use ($videoService, &$processed) {
            $videoPublicPath = $media->url; // e.g. posts/xxx.mp4 stored on public disk
            $fullVideoPath = storage_path('app/public/' . ltrim($videoPublicPath, '/'));

            if (!file_exists($fullVideoPath)) {
                $this->warn("File not found: {$fullVideoPath} (media id {$media->id})");
                return;
            }

            $thumbRelative = $videoService->generateThumbnail($fullVideoPath, 'posts');
            if ($thumbRelative) {
                $media->thumbnail_url = $thumbRelative;
                $media->save();
                $processed++;
                $this->line("Thumbnail generated for media id {$media->id}: {$thumbRelative}");
            } else {
                $this->warn("Failed to generate thumbnail for media id {$media->id}");
            }
        });

        $this->info("Done. Thumbnails generated: {$processed}");
        return self::SUCCESS;
    }
}
