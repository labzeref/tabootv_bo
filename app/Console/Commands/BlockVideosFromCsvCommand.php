<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlockVideosFromCsvCommand extends Command
{
    protected $signature = 'block:videos-from-csv';

    protected $description = 'Command description';

    public function handle(): void
    {
        DB::table('videos')->update(['is_blocked' => true]);

        $keepVideosCsv = database_path('source/keep-videos.csv');

        if (($handle = fopen($keepVideosCsv, 'r')) !== false) {

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

            $this->info('Videos have been blocked successfully');
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
