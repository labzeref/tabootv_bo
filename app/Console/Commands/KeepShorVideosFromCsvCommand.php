<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class KeepShorVideosFromCsvCommand extends Command
{
    protected $signature = 'keep:short-videos-from-csv';

    protected $description = 'Command description';

    public function handle(): void
    {
        $keepShortVideosCsv = database_path('source/keep-short-videos.csv');

        if (($handle = fopen($keepShortVideosCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $media = Media::findOrFail($data[$columns['folder_name']]);
                $video = Video::withoutGlobalScope('allowed')->findOrFail($media->model_id);

                $video->update([
                    'is_blocked' => false,
                    'title' => $data[$columns['video_title']],
                    'description' => $data[$columns['video_description']],
                ]);
            }

            fclose($handle);
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
