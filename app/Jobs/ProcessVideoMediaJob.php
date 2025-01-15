<?php

namespace App\Jobs;

use App\Models\Series;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class ProcessVideoMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Video   $video,
        private readonly ?string $thumbnailPath = null,
        private readonly ?string $videoPath = null
    )
    {
        $this->queue = 'conversion';
    }

    public function handle(): void
    {
        if ($this->thumbnailPath) {
            $this->video->addMedia($this->thumbnailPath)->toMediaCollection('thumbnail');
        }

        if ($this->videoPath) {
            $media = $this->video->addMedia($this->videoPath)->toMediaCollection('video');

            transcodeVideo($media->refresh(), $this->video->short);
        }

        $this->video->update(['processing' => false]);
    }

    public function fail(): void
    {
        $this->video->update(['processing' => false]);
    }
}
