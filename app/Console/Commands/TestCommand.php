<?php

namespace App\Console\Commands;

use App\Models\Series;
use App\Models\Video;
use App\Services\AppleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class TestCommand extends Command
{
    protected $signature = 'test';

    protected $description = 'Command description';

    public function handle(): void
    {
    }

//    public function handle(): void
//    {
//        $series3 = Series::withoutGlobalScope('allowed')->find(3);
//        $path3 = Video::withoutGlobalScope('allowed')->find(309)->getFirstMediaPath('video', '1080');
//        $url3 = _getTemUrl($path3);
//
//
//        $series4 = Series::withoutGlobalScope('allowed')->find(4);
//        $path4 = Video::withoutGlobalScope('allowed')->find(312)->getFirstMediaPath('video', '1080');
//        $url4 = _getTemUrl($path4);
//
//        dd($url3, $url4);
//
//        $this->info('done');
//
//    }

//    public function handle(): void
//    {
//        foreach (glob(storage_path('/series_thumbnails/*')) as $file) {
//            $seriesId = pathinfo($file, PATHINFO_FILENAME);
//
//            $series = Series::withoutGlobalScope('allowed')->findOrFail($seriesId);
//
//            $series->addMedia($file)->toMediaCollection('trailer_thumbnail');
//        }
//
//        dd(_getTemUrl(Series::withoutGlobalScope('allowed')->find(1)->getFirstMediaPath('trailer_thumbnail')));
//    }

//    public function handle(): void
//    {
//        foreach (Video::withoutGlobalScope('allowed')->orderBy('id')->get() as $video) {
//            $media = $video->getFirstMedia('thumbnail');
//            if (Storage::disk('s3')->fileExists($media->getPath())) {
//                if (!Storage::disk('s3')->fileExists($media->getPath('card'))) {
//                    Artisan::call("media-library:regenerate --ids=$media->id --only=card");
//                    $this->info("card video id: $video->id media id : $media->id");
//                }
//
//                if (!Storage::disk('s3')->fileExists($media->getPath('featured'))) {
//                    Artisan::call("media-library:regenerate --ids=$media->id --only=featured");
//                    $this->info("featured video id: $video->id media id : $media->id");
//                }
//            } else {
//                $this->error("Thumbnail not exists for video: $video->id");
//            }
//        }
//
//        $this->info('All done');
//
//    }
}
