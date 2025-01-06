<?php

namespace App\Console\Commands;

use App\Models\Series;
use Illuminate\Console\Command;

class FixVideosDisplayOrderCommand extends Command
{
    protected $signature = 'fix:videos-display-order';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach (Series::withoutGlobalScope('allowed')->get() as $series) {
            $series->videos()->withoutGlobalScope('allowed')->orderBy('id')->get()->each(function ($video, $index) {
                $video->update(['display_order' => $index + 1]);
            });
        }
    }
}
