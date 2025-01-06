<?php

namespace App\Console\Commands;

use App\Models\Series;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class addThumbnailToSeries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-thumbnail-to-series';

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
        $video = Series::find($this->ask('series_id'));

        if (!$video) {
            $this->error('series not found');
            return;
        }

        $path = Storage::disk('s3')->temporaryUrl('temp/'.$this->ask('path'),now()->addDay());

        $video->addMediaFromUrl($path)
            ->toMediaCollection('trailer_thumbnail');

    }
}
