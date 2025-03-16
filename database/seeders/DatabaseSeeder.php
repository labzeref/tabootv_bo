<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PostComment;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment('local')) {

                $this->call([
                    CategorySeeder::class,
                    TagsTableSeeder::class,
                    CountrySeeder::class,
                    PlanSeeder::class,
                    UserSeeder::class,
                    ChannelSeeder::class,
                    LongVideoSeeder::class,
                    ShortVideoSeeder::class,
                    VideosSeeder::class,
                    ChatroomSeeder::class,
                    PostSeeder::class,
                    CommentsTableSeeder::class,
                    PostCommentSeeder::class,
                ]);
          }else{
                $this->call([

                    TagsTableSeeder::class,
                    CountrySeeder::class,
                    PlanSeeder::class,
                    UserSeeder::class,
                    ChannelSeeder::class,
                    LongVideoSeeder::class,
                    ShortVideoSeeder::class,
        //            VideosSeeder::class,
                    ChatroomSeeder::class,
                    PostSeeder::class,
                    CommentsTableSeeder::class,
                    PostCommentSeeder::class,


                ]);
         }
    }


}
