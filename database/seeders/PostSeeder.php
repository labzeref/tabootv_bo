<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //     $table->foreignId('user_id');
        //            $table->string('caption');

        $posts = [
            [
                'user_id' => 1,
                'caption' => 'The DesignLab is doing major work on Community-Driven Design, with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below',
                'post-image-path' => storage_path('/images/videos/shorts/img1.png'),
            ],
            [
                'user_id' => 1,
                'caption' => 'The DesignLab is doing major work on Community-Driven Design, with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below',
                'post-image-path' => storage_path('/images/videos/shorts/img1.png'),
            ],
            [
                'user_id' => 1,
                'caption' => 'The DesignLab is doing major work on Community-Driven Design, with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below',
                'post-image-path' => storage_path('/images/videos/shorts/img1.png'),
            ],
            [
                'user_id' => 1,
                'caption' => 'The DesignLab is doing major work on Community-Driven Design, with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below with several different variants. If you are working in this area, submit a paper to the DIS conference here in San Diego (see DesignLab posting below',
                'post-image-path' => storage_path('/images/videos/shorts/img1.png'),
            ],
        ];

        foreach ($posts as $post) {
           $record =  Post::create(Arr::except($post, ['post-image-path']));
            $record->likes()->create(['user_id' => 1,'value' => true]);
            $record->likes()->create(['user_id' => 2,'value' => true]);
            $record->likes()->create(['user_id' => 3,'value' => true]);
            $record->likes()->create(['user_id' => 4,'value' => true]);

            $record->dislikes()->create(['user_id' => 5,'value' => false]);


           $record->addMedia($post['post-image-path'])->preservingOriginal()
               ->toMediaCollection('post-image');
        }
    }
}
