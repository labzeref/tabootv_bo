<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExportLongVideosCsvForThumbnailsCommand extends Command
{
    protected $signature = 'export:long-videos-csv-for-thumbnails';

    protected $description = 'Command description';

    public function handle(): void
    {
        $columns = [
            'folder_name',
            'file_name',
            'title',
            'link',
        ];

        $time = time();

        $filename = "long-videos-with-link-for-thumbnails-{$time}.csv";

        $file = fopen(storage_path("app/{$filename}"), 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        fputcsv($file, $columns);

        foreach (Video::withoutGlobalScope('allowed')->with('media')->where('short', false)->orderBy('id')->get() as $video) {
            $media = $video->getFirstMedia('video');
            echo "\n #$media->id";

            $path = $media->getPath();

            if (!Storage::fileExists($path)) {
                $path = str_replace('.mp4', '.webm', $path);

                if (!Storage::fileExists($path)) {
                    dd("not a valid url", $path);
                }
            }

            $row = [
                'folder_name' => $media->id,
                'file_name' => $media->file_name,
                'title' => $video->title,
                'link' => Storage::temporaryUrl($path, now()->addDays(7)),
            ];

            $row = array_values($row);

            fputcsv($file, $row);
        }
    }
}
