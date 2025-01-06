<?php

namespace App\Console\Commands;

use App\Models\Series;
use App\Models\Video;
use Illuminate\Console\Command;

class CopyWebBlockToAppBlockCommand extends Command
{
    protected $signature = 'copy:web-block-to-app-block';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach (Series::withoutGlobalScope('allowed')->get() as $series) {
            $series->update(['is_app_blocked' => $series->is_blocked]);
        }

        foreach (Video::withoutGlobalScope('allowed')->get() as $series) {
            $series->update(['is_app_blocked' => $series->is_blocked]);
        }
    }
}
