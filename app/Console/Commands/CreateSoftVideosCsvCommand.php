<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CreateSoftVideosCsvCommand extends Command
{
    protected $signature = 'create:soft-videos-csv';

    protected $description = 'Command description';

    public function handle(): void
    {
        $keepVideosCsv = database_path('source/keep-videos.csv');


        if (($handle = fopen($keepVideosCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            $newColumns = [
                'folder_name',
                'file_name',
                'generated_conversions',
                'video_title',
                'video_description',
                'link',
            ];

            $time = time();

            $newFileName = "soft-videos-{$time}.csv";

            $newFile = fopen(storage_path("app/{$newFileName}"), 'w');

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $newFileName . '"');

            fputcsv($newFile, $newColumns);

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $media = Media::findOrFail($data[$columns['folder_name']]);

                $row = [
                    'folder_name' => $data[$columns['folder_name']],
                    'file_name' => $data[$columns['file_name']],
                    'generated_conversions' => $data[$columns['generated_conversions']],
                    'video_title' => $data[$columns['video_title']],
                    'video_description' => $data[$columns['video_description']],
                    'link' => $media->getTemporaryUrl(now()->addDays(7)),
                ];

                $row = array_values($row);

                fputcsv($newFile, $row);
            }

            fclose($handle);
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
