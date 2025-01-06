<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FixS3NamingConventionCommand extends Command
{
    protected $signature = 'fix:s3-naming-convention';

    protected $description = 'Command description';

    public function handle(): void
    {

        $ids = [
            '12',
            '16',
            '18',
            '26',
            '34',
            '36',
            '97',
            '105',
            '117',
            '123',
            '135',
            '141',
            '143',
            '151',
            '153',
            '163',
            '169',
            '383',
            '429',
        ];

        foreach ($ids as $id) {
            $media = Media::findOrFail($id);

            $path = $media->getPath();
            $path720 = $media->getPath('720');
            $path480 = $media->getPath('480');
            $path1080 = $media->getPath('1080');

            $bucketDir = config('media-library.prefix').'/'.$media->id.'/';

            $this->renameFileToLowercase($bucketDir, $path);
            $this->renameFileToLowercase($bucketDir.'/conversions/', $path720);
            $this->renameFileToLowercase($bucketDir.'/conversions/', $path480);
            $this->renameFileToLowercase($bucketDir.'/conversions/', $path1080);


        }
    }

    private function renameFileToLowercase($bucketDirectory, $newPath)
    {
        // List all files in the directory (or bucket)
        $allFiles = Storage::disk('s3')->files($bucketDirectory);

        // Extract the lowercase filename
        $newFileName = basename($newPath);

        // Find the case-insensitive match for the old file
        $oldPath = null;
        foreach ($allFiles as $file) {
            if (strtolower(basename($file)) === $newFileName) {
                $oldPath = $file; // Store the actual case-sensitive old path
                break;
            }
        }

        if ($oldPath && $oldPath !== $newPath) {
            // Copy the file to the new lowercase path
            Storage::disk('s3')->copy($oldPath, $newPath);
            Storage::disk('s3')->delete($oldPath);
            $this->info("File renamed successfully to: $newPath.");

            return;
        }

        $this->error("No file found matching the new path in a case-insensitive manner.");
    }

}
