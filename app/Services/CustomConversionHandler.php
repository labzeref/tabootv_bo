<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer;

class CustomConversionHandler extends DefaultFileNamer
{
    public function __invoke(string $sourcePath, string $conversionPath): void
    {
        dd($sourcePath, $conversionPath);
        exec("ffmpeg -i $sourcePath -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p -c:a aac -b:a 128k -movflags +faststart $conversionPath");
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($sourcePath);

        $format = new X264('aac', 'libx264');
        $format->setAdditionalParameters(['-crf', '23', '-preset', 'fast', '-pix_fmt', 'yuv420p', '-movflags', '+faststart']);

        $video->save($format, $conversionPath);
    }
}
