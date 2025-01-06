<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class checkMissingSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-missing-sales';

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

        $paidMissingPeople = 0;

        $csvFile = fopen(base_path("database/sales/sales.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {

                if (DB::table('users')->where('email', $data[0])->doesntExist()) {
                    $paidMissingPeople++;
                }

            }
            $firstline = false;
        }

        dd($paidMissingPeople);

    }
}
