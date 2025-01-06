<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $video = Video::first();
        $tag = Tag::first();

        if ($video && $tag) {
            $video->tags()->attach($tag->id);
        }
    }
}
