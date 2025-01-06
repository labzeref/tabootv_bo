<?php

namespace App\Console\Commands;

use App\Models\Series;
use Illuminate\Console\Command;

class BlockSeriesAndItsVideosCommand extends Command
{
    protected $signature = 'block:series-and-its-videos';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach (Series::withoutGlobalScope('allowed')->get() as $series) {
            $series->update(['is_blocked' => true]);
            $series->videos()->withoutGlobalScope('allowed')->get()->each(function ($video) {
                $video->update(['is_blocked' => true]);
            });
        }
    }
}
