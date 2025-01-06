<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use App\Models\Series;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SeriesSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;

        $seriesCsv = database_path('source/series.csv');


        if (($handle = fopen($seriesCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                $randomFile = storage_path('/images/series/desktop_banner.png');

                $series = Series::create([
                    'user_id' => $userId,
                    'title' => $data[$columns['title']],
                    'description' => $data[$columns['description']],
                    'trailer' => '#', //trailer was link for trailer video will remove latter
                    'published_at' => now(),
                ]);

//                $series->addMedia($randomFile)
//                    ->preservingOriginal()
//                    ->toMediaCollection('thumbnail');
            }

            fclose($handle);
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
