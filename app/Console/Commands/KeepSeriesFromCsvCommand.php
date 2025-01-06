<?php

namespace App\Console\Commands;

use App\Models\Series;
use Illuminate\Console\Command;

class KeepSeriesFromCsvCommand extends Command
{
    protected $signature = 'keep:series-from-csv';

    protected $description = 'Command description';

    public function handle(): void
    {
        $keepSeriesCsv = database_path('source/keep-series.csv');

        if (($handle = fopen($keepSeriesCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $series = Series::withoutGlobalScope('allowed')->where('id', $data[$columns['series_id']])->first();

                $series->update([
                    'title' => $data[$columns['series_title']],
                    'is_blocked' => false
                ]);
            }

            fclose($handle);

        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
