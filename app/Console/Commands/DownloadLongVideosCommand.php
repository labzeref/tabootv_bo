<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DownloadLongVideosCommand extends Command
{
    protected $signature = 'download:long-videos';

    protected $description = 'Command description';

    public function handle(): void
    {
        $this->download('https://rumble.com/vtg4o6-black-cop-expose-blm-lies-in-epic-2-min-video.html', 'rumble');
//        $this->download('https://www.youtube.com/watch?v=f41juaJMxKE', 'youtube');
    }

    public function download(string $videoUrl, string $platform)
    {
        $cookiesPath = storage_path('/cookies.txt');

        [$bestVideoFormat, $bestAudioFormat] = $this->getBestQuality($videoUrl, $platform);

        if ($bestVideoFormat) {
            echo "Selected best video format: $bestVideoFormat\n";
            echo "Selected best audio format: $bestAudioFormat\n";

            // Paths for output files
            $videoFile = uniqid() . '_video.webm'; // Handle generic video format
            $audioFile = uniqid() . '_audio.m4a';
            $mergedFile = uniqid() . '_merged.mp4'; // Final file in mp4 format
            $outputDir = storage_path('app/public/videos');

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Step 1: Download the best video
            exec("yt-dlp --cookies $cookiesPath -f $bestVideoFormat -o $outputDir/$videoFile $videoUrl", $outputVideo, $returnVarVideo);

            dump($outputVideo);

            if ($returnVarVideo !== 0) {
                dd("Failed to download video: ");
            }

            if ($bestAudioFormat) {
                // Step 2: Download the best audio
                exec("yt-dlp --cookies $cookiesPath -f $bestAudioFormat -o $outputDir/$audioFile $videoUrl", $outputAudio, $returnVarAudio);
                if ($returnVarAudio !== 0) {
                    dd("Failed to download audio: " . implode("\n", $outputAudio));
                }

                // Step 3: Merge video and audio
                exec(
                    "ffmpeg -i $outputDir/$videoFile -i $outputDir/$audioFile -c:v copy -c:a aac -strict experimental $outputDir/$mergedFile",
                    $outputMerge,
                    $returnVarMerge
                );

                // Output the final file location
                if ($returnVarMerge !== 0) {
                    dd("Failed to merge video and audio: " . implode("\n", $outputMerge));
                }

                echo "Merged video created: " . asset("storage/videos/$mergedFile") . "\n";

                // Clean up temporary files
//                unlink("$outputDir/$videoFile");
//                unlink("$outputDir/$audioFile");
            } else {
                // Output the final file location
                echo "Video created: " . asset("storage/videos/$videoFile") . "\n";
            }
        } else {
            echo "Could not find suitable video or audio formats.\n";
        }
    }

    public function getBestQuality(string $videoUrl, string $platform)
    {
        $cookiesPath = storage_path('/cookies.txt');

        if ($platform == 'youtube') {
            // Build the yt-dlp command
            $process = new Process([
                'yt-dlp',
                '--cookies',
                $cookiesPath,
                '-F', // List all available formats
                $videoUrl,
            ]);

            $process->run();

            // Check if the process executed successfully
            if (!$process->isSuccessful()) {
                $this->error('Error fetching formats: ' . $process->getErrorOutput());
                return;
            }

            $output = $process->getOutput();

            // Parse the output to find the best video and audio formats
            $lines = explode("\n", $output);
            $videoFormats = [];
            $audioFormats = [];

            foreach ($lines as $line) {
                if (preg_match('/^\s*(\d+)\s+[^|\n]+\s+(\d+x\d+|\d+p)\s+.*video/', $line, $matches)) {
                    $videoFormats[] = [
                        'format_id' => $matches[1],
                        'resolution' => $matches[2],
                    ];
                } elseif (preg_match('/^\s*(\d+)\s+[^|\n]+\s+audio/', $line, $matches)) {
                    $audioFormats[] = [
                        'format_id' => $matches[1],
                    ];
                }
            }

            // Get the best video and audio formats (based on resolution/quality)
            $bestVideo = end($videoFormats); // Assuming the last one is the best
            $bestAudio = end($audioFormats); // Assuming the last one is the best

            $bestVideoFormat = $bestVideo ? $bestVideo['format_id'] : null;
            $bestAudioFormat = $bestAudio ? $bestAudio['format_id'] : null;

            return [$bestVideoFormat, $bestAudioFormat];
        } else {
            $process = new Process([
                'yt-dlp',
                '-F', // List all available formats
                $videoUrl,
            ]);

            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Error fetching formats: ' . $process->getErrorOutput());
            }

            $output = $process->getOutput();

// Parse formats dynamically from yt-dlp output
            $lines = explode("\n", $output);
            $videoFormats = [];

            foreach ($lines as $line) {
                // Match formats like: "mp4-1080p mp4 1920x1080 30 | ..."
                if (preg_match('/^\s*(\S+)\s+(\S+)\s+(\d+x\d+)\s+(\d+)?\s*\|/i', $line, $matches)) {
                    $videoFormats[] = [
                        'format_id' => $matches[1],   // Format ID (e.g., mp4-720p, webm-480p)
                        'ext' => $matches[2],        // Extension (e.g., mp4, webm)
                        'resolution' => $matches[3], // Resolution (e.g., 1920x1080)
                        'fps' => isset($matches[4]) ? (int)$matches[4] : null, // FPS (optional)
                    ];
                }
            }

            if (empty($videoFormats)) {
                throw new \RuntimeException("No valid video formats found for the provided URL.");
            }

// Sort formats by resolution (highest resolution first)
            usort($videoFormats, function ($a, $b) {
                // Calculate resolution as width * height
                $aRes = preg_match('/(\d+)x(\d+)/', $a['resolution'], $aMatch) ? $aMatch[1] * $aMatch[2] : 0;
                $bRes = preg_match('/(\d+)x(\d+)/', $b['resolution'], $bMatch) ? $bMatch[1] * $bMatch[2] : 0;

                return $bRes <=> $aRes; // Sort descending by resolution
            });

            return [$videoFormats[0]['format_id'], null]; // Return the format ID of the best video
        }
    }
}
