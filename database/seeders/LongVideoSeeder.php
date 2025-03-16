<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LongVideoSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;

        $longVideoCsv = database_path('source/long-videos.csv');


        if (($handle = fopen($longVideoCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            $i = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $i++;

                if ($i < 32) {
                    continue;
                }

                $directory = glob(storage_path('/images/videos/long/*'));
                $randomFile = $directory[array_rand($directory)];

                $video = Video::create([
                    'user_id' => $userId,
                    'country_id' => Country::where('name', 'iLIKE', $data[$columns['country']])->orWhere('iso', 'iLIKE', $data[$columns['country']])->first()?->id,
                    'title' => $data[$columns['title']],
                    'description' => $data[$columns['description']],
                    'location' => $data[$columns['location']],
                    'short' => false,
                    'featured' => false,
                    'banner' => false,
                    'published_at' => Carbon::parse($data[$columns['publish_at']]),
                    'duration' => 746,
                ]);

                $categories = explode(",", trim(preg_replace('/\s+/', '', $data[$columns['categories']])));

                foreach ($categories as $category) {
                    $category = Category::updateOrCreate(['name' => $category]);
                    $video->categories()->attach($category->id);
                }

//                $videoPath = _downloadVideo($data[$columns['link']], $data[$columns['platform']]);

                $video->addMedia($randomFile)
                    ->preservingOriginal()
                    ->toMediaCollection('thumbnail');

//                $video->addMedia($videoPath)
//                    ->toMediaCollection('video');
            }

            fclose($handle);
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
