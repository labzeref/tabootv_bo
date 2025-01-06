<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateLongVideosThumbnailsCommand extends Command
{
    protected $signature = 'update:long-videos-thumbnails';

    protected $description = 'Command description';

    public function handle(): void
    {
        $images = glob(storage_path('/thumbnails/*'));

        foreach ($images as $image) {
            $mediaId = pathinfo($image)['filename'];

            $media = Media::find($mediaId);

            $video = Video::withoutGlobalScope('allowed')->find($media->model_id);

            $video->addMedia($image)->preservingOriginal()->toMediaCollection('thumbnail');

            $this->info("Thumbnail for video {$video->id} updated");
        }
    }
}
