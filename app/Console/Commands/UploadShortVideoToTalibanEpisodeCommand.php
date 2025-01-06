<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;

class UploadShortVideoToTalibanEpisodeCommand extends Command
{
    protected $signature = 'upload:short-video-to-taliban-episode';

    protected $description = 'Command description';

    public function handle(): void
    {
        $video = Video::withoutGlobalScope('allowed')->findOrFail(318);

        $video->addMedia(storage_path('/short_video.mp4'))->toMediaCollection('video');

        dd(_getTemUrl($video->getFirstMediaPath('video')));
    }
}
