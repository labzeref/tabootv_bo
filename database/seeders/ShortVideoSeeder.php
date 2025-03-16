<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShortVideoSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;

        $longVideoCsv = database_path('source/short-videos.csv');


        if (($handle = fopen($longVideoCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            $i = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $i++;

                if ($i < 112) {
                    continue;
                }

                $video = Video::create([
                    'user_id' => $userId,
                    'country_id' => null,
                    'title' => $data[$columns['title']],
                    'description' => null,
                    'location' => null,
                    'short' => true,
                    'featured' => false,
                    'banner' => false,
                    'published_at' => Carbon::parse($data[$columns['publish_at']]),
                    'duration' => 746,
                ]);

                $directory = glob(storage_path('/images/videos/shorts/*'));
                $randomFile = $directory[array_rand($directory)];

                $video->addMedia($randomFile)
                    ->preservingOriginal()
                    ->toMediaCollection('thumbnail');

//                $videoPath = _downloadVideo($data[$columns['link']], 'youtube');

//                try {
//                    $video->addMedia(storage_path('/app/public/object2.mp4'))
//                    ->preservingOriginal()
//                    ->toMediaCollection('video');
//                } catch (\Throwable $throwable) {
//                    dd($throwable);
//                }

//                dd($video->refresh()->getFirstTemporaryUrl(now()->addDay(), 'video', '720p'));
//                dd($video->refresh()->getFirstMediaUrl('video', '720p'));
            }


            fclose($handle);
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
