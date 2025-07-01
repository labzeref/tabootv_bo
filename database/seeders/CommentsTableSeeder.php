<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'video_id' => 1, // Assuming a video with ID 1 exists
            'content' => 'This is a sample comment.',
        ]);
    }
}
