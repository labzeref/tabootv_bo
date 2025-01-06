<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportOldUsersCommand extends Command
{
    protected $signature = 'import:old-users';

    protected $description = 'Command description';

    public function handle(): void
    {
        $oldUsersCsv = database_path('source/old-users.csv');

        if (($handle = fopen($oldUsersCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                if (DB::table('users')->where('email', 'iLIKE', $data[$columns['email']])->exists()) {
                    continue;
                }

                User::create([
                    'email' => $data[$columns['email']],
                    'first_name' => $data[$columns['first_name']],
                    'last_name' => $data[$columns['last_name']],
                    'display_name' => $data[$columns['username']],
                    'lifetime_membership' => $data[$columns['active_sub_count']] > 0,
                    'password' => Hash::make(Str::random()),
                ]);
            }
            fclose($handle);
        } else {
            throw new \Exception('Cannot open world.csv');
        }

        $this->info("Old users imported successfully");
    }
}
