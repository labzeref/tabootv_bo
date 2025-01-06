<?php

namespace App\Console\Commands;

use App\Models\Series;
use App\Models\Video;
use Illuminate\Console\Command;

class KeepSeriesVideosFromCsvCommand extends Command
{
    protected $signature = 'keep:series-videos-from-csv';

    protected $description = 'Command description';

    public function handle(): void
    {
        $keepSeriesVideosCsv = database_path('source/keep-series-videos.csv');

        if (($handle = fopen($keepSeriesVideosCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $video = Video::withoutGlobalScope('allowed')->where('series_id', $data[$columns['series_id']])->where('display_order', $data[$columns['video_display_order']])->first();

                $video->update([
                    'title' => $data[$columns['video_title']],
                    'description' => $data[$columns['video_description']],
                    'is_blocked' => false
                ]);
            }

            fclose($handle);

        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
