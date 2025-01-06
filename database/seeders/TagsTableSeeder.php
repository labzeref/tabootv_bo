<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tages = ['Journalism', 'Travel', 'Food', 'Danger', 'Adventure', 'Journalism', 'Travel', 'Food', 'Danger', 'Adventure', 'Journalism', 'Travel', 'Food'];
        foreach ($tages as $tage) {
            Tag::create(['name' => $tage]);
        }
    }
}
