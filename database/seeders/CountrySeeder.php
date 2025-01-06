<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countryCsv = database_path('source/countries.csv');
        $now = now();

        $storedCountries = Country::all()->toArray();

        $payload = [];

        if (($handle = fopen($countryCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $payload[] = [
                    'name' => $data[$columns['name']],
                    'iso' => $data[$columns['iso']],
                    'emoji' => $data[$columns['emoji']],
                    'emoji_code' => $data[$columns['emoji_code']],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            fclose($handle);

            $payload = array_filter($payload, function ($country) use ($storedCountries) {
                return !in_array($country['iso'], array_column($storedCountries, 'iso'));
            });

            DB::table('countries')->insert($payload);
        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
