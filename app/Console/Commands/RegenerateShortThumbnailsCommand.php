<?php

namespace App\Console\Commands;

use FFMpeg\Coordinate\TimeCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use FFMpeg\FFMpeg;

class RegenerateShortThumbnailsCommand extends Command
{
    protected $signature = 'regenerate:short-thumbnails';

    protected $description = 'Command description';

    public function handle(): void
    {
        $videos = \App\Models\Video::withoutGlobalScope('allowed')->where('short', true)->orderBy('id')->get();

        foreach ($videos as $video) {
            /** @var Media $media */
            $media = $video->getFirstMedia('video');
            $this->info("Regenerated short thumbnail for Media {$media->id}");

            $command = "php artisan media-library:regenerate --ids={$media->id} --only=video_thumbnail --force";
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                $this->error("Command failed for Media ID {$media->id}: " . implode("\n", $output));
            } else {
                $this->info("Command executed successfully for Media ID {$media->id}");
            }

        }
    }
}
