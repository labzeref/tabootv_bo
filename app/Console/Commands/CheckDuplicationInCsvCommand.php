<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Country;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckDuplicationInCsvCommand extends Command
{
    protected $signature = 'check:duplication-in-csv';

    protected $description = 'Command description';

    public function handle(): void
    {
        $usersCsv = database_path('/copecart/sales-copecart-users.csv');
        $emails = [];

        if (($handle = fopen($usersCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $email = $data[$columns['Customer email']];

                if (in_array($email, $emails)) {
                    $this->info('Duplicate email: ' . $email);
                } else {
                    $emails[] = $email;
                }
            }

            fclose($handle);

        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
