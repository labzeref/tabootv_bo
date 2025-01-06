<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AddThumbnailToVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-thumbnail-to-video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $video = Video::withoutGlobalScope('allowed')->where('id',$this->ask('video_id'))->first();

        if (!$video) {
            $this->error('video not found');
            return;
        }

        $path = Storage::disk('s3')->temporaryUrl($this->ask('path'),now()->addDay());

        $video->addMediaFromUrl($path)
            ->toMediaCollection('thumbnail');

        $this->info('Thumbnail added successfully');

    }
}
