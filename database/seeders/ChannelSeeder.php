<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::create([
            'user_id' => 1,
            'name' => 'Arabuncut',
            'description' => 'A sample description for the channel.',
        ]);

        $channel->addMedia(storage_path('/images/channel/arabuncut-dp.jpg'))
        ->preservingOriginal()
        ->toMediaCollection('dp');

        $channel->addMedia(storage_path('/images/channel/arabuncut-banner.jpg'))
        ->preservingOriginal()
        ->toMediaCollection('banner');
    }
}
