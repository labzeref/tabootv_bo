<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ConvertAllVideosCommand extends Command
{
    protected $signature = 'convert:all-videos';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach (Media::where('mime_type', 'video/mp4')->orderBy('id')->get() as $media) {
            event(new MediaHasBeenAddedEvent($media));
        }
    }


}
