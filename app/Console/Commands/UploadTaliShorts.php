<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\Video;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UploadTaliShorts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-tali-shorts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $csvFile = fopen(base_path("database/shorts/tali.csv"), "r");
        $firstline = true;
        $count = 0;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $count++;
            if (!$firstline) {
                $title = $data[0];
                $fileName = $data[1];
                $link = $data[4];

                $this->info('=============================================');
                $this->info('Processing Row number: '.$count.' for Title:' . $title);
                $this->info('downloading video...');
                $video_file = $this->videoFile($link);
                if (!$video_file) {
                    $this->error(' Row number: '.$count.' failed for Title:' . $title);
                    continue;
                }

                $this->info('video file: ' . $video_file);


                $video = Video::create([
                    'user_id' => 1,
                    'country_id' => 1,
                    'title' => $title,
                    'description' => null,
                    'location' => null,
                    'short' => true,
                    'featured' => false,
                    'banner' => false,
                    'published_at' => now(),
                    'duration' => 746,
                ]);

                try {
                    $this->info('uploading video...');
                    $mediaItem = $video->addMedia($video_file)
                        ->toMediaCollection('video');
                    $this->info('converting video...');
                    transcodeVideo($mediaItem, true);
                    $this->info('video uploaded...');
                } catch (\Throwable $throwable) {
                    $this->error(' Row number: '.$count.' failed for Title:' . $title);
                    $this->error($throwable->getMessage());
                }
            }
            $firstline = false;
        }
    }

    public function videoFile($link): ?string
    {
        Storage::disk('app')->makeDirectory('temp');

        try {
            // 1) Check if it's a Google Drive link and extract fileId
            if (strpos($link, 'drive.google.com') === false) {
                return null;
            }
            preg_match('/\/file\/d\/(.*?)\//', $link, $matches);
            if (!isset($matches[1])) {
                $this->error('Invalid Google Drive URL: ' . $link);
                return null;
            }
            $fileId = $matches[1];

            $fileName = "video_file{$fileId}.mp4";
            $tempPath = storage_path("app/temp/{$fileName}");

            // 2) If we already downloaded this file, return its path
            if (Storage::disk('app')->exists("temp/{$fileName}")) {
                return $tempPath;
            }

            // 3) Construct the initial download URL
            //    (the typical “uc?export=download&id=...” approach)
            $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";

            $client = new Client([
                'headers' => [
                    // Setting a user agent can sometimes help
                    'User-Agent' => 'Mozilla/5.0',
                ],
                'allow_redirects' => true,
            ]);

            // 4) First attempt: might get the “virus scan” HTML
            $response = $client->get($downloadUrl, ['stream' => true]);

            // Read the first chunk (or convert to string) to see if it's HTML
            // We can do getContents(), but let's just do:
            $htmlBody = (string) $response->getBody();

            // If the Content-Type is text/html, let's check if we need confirm token
            $contentType = $response->getHeaderLine('Content-Type');

            if (str_contains($contentType, 'text/html') &&
                str_contains($htmlBody, 'Google Drive can\'t scan this file')) {

                // 5) We have the “virus scan warning” page.
                //    Extract the confirm token from the hidden input.
                if (preg_match('/<input[^>]*name="confirm"[^>]*value="([^"]+)"/', $htmlBody, $m)) {
                    $confirmToken = $m[1];

                    // Build the second request URL
                    // In the form, action is "https://drive.usercontent.google.com/download"
                    // with query string: id, export=download, confirm.
                    $confirmUrl = "https://drive.usercontent.google.com/download"
                        . "?id={$fileId}"
                        . "&export=download"
                        . "&confirm={$confirmToken}";

                    // Re-issue the GET request for the actual file
                    $response = $client->get($confirmUrl, ['stream' => true]);
                } else {
                    // If we fail to find the confirm token, log and return null
                    $this->error('Could not find confirm token in virus-scan page.');
                    return null;
                }
            } else {
                file_put_contents($tempPath, $htmlBody);
            }

            $bodyStream = $response->getBody();
            $fileStream = fopen($tempPath, 'w');
            while (!$bodyStream->eof()) {
                fwrite($fileStream, $bodyStream->read(1024 * 8));
            }
            fclose($fileStream);

            return $tempPath;

        } catch (\Exception $e) {
            $this->error('Failed to download from Google Drive: ' . $e->getMessage());
            return null;
        }
    }
}
