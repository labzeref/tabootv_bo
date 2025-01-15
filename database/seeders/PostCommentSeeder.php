<?php

namespace Database\Seeders;

use App\Models\PostComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                PostComment::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'post_id' => 1, // Assuming a video with ID 1 exists
            'content' => 'This is a sample comment.',
        ]);
        $postComments = PostComment::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'post_id' => 2, // Assuming a video with ID 1 exists
            'content' => 'This is a sample comment.',
        ]);
        $postComments->likes()->create([
            'user_id' => 1,
            'value' => true,
        ]);
        $postComments = PostComment::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'post_id' => 3, // Assuming a video with ID 1 exists
            'content' => 'This is a sample comment.',
        ]);
        $postComments->likes()->create([
            'user_id' => 1,
            'value' => false,
        ]);
        $postComments = PostComment::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'post_id' => 4, // Assuming a video with ID 1 exists
            'content' => 'This is a sample comment.',
        ]);
        $postComments->likes()->create([
            'user_id' => 1,
            'value' => true,
        ]);
    }
}
