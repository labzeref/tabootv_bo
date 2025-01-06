<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestBlockedSerise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-blocked-series';

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

        $user = User::where('email','akash@zereflab.com')->first();
        $blockedSeriesIds = auth()->user()->blockedSeries->pluck('id')->toArray();
        $series = SeriesResource::collection(Series::whereNotIn('id', $blockedSeriesIds)->with('user','videos')->withCount(['videos'])->limit(10)->get());


    }
}
