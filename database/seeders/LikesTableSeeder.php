<?php

namespace Database\Seeders;

use App\Models\VideoLike;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VideoLike::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'video_id' => 1, // Assuming a video with ID 1 exists
        ]);
    }
}
