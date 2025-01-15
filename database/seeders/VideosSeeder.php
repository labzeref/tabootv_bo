<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use App\Models\Series;
use App\Models\Comment;
use App\Models\VideoCategory;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VideosSeeder extends Seeder
{
    /**
    1
    2
    3
    4
    5
    6

    7
     *
     * ['Journalism', 'Travel', 'Food', 'Danger', 'Adventure', 'Journalism', 'Travel', 'Food', 'Danger', 'Adventure', 'Journalism', 'Travel', 'Food']
     */
    public function run(): void
    {
        $userId = 1;

        $videos = [
            // Shorts
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Top 10 Life-Changing Productivity Hacks" Descriptio',
                'description' => 'Discover game-changing productivity tips that will transform the way you work',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img1.png'),
                'category_id' => 1,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'The Ultimate Travel Guide to Bali in 2024',
                'description' => 'Explore the must-visit spots, hidden gems, and travel tips',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img2.png'),
                'category_id' => 2,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'How to Cook the Perfect Steak at Home',
                'description' => 'Master the art of steak-making with this step-by-',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img3.png'),
                'category_id' => 3,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => '5 Mind-Bending Science Experiments You Can D',
                'description' => 'Experience jaw-dropping experiments that will amaze you and your friends using',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img4.png'),
                'category_id' => 4,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Beginner’s Guide to Investing in 2024',
                'description' => 'Learn the basics of investing and start your journey toward financial independence with',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img5.png'),
                'category_id' => 5,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'A Day in My Life as a Digital Noma',
                'description' => 'Follow my routine as a digital nomad, balancing work, travel,',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img6.png'),
                'category_id' => 6,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Exploring the Deep Sea: Mysteries of the Ocea',
                'description' => 'Dive into the unexplored depths of the ocean and uncover fascinating secrets about',
                'short' => true,
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'thumbnail_path' => storage_path('/images/videos/shorts/img7.png'),
                'category_id' => 7,
            ],
            // Longs
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'I tips that will transform the',
                'description' => 'Accelerate your language learning with proven tips and tricks that polyglots swear by',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'banner' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                'category_id' => 8,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'The German City with NO LAWS',
                'description' => 'Check out our unboxing and detailed first look at Apple’s latest',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'banner' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                'category_id' => 9,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => '10 Tips for Learning a New Language Fast ',
                'description' => 'Transform your living space with these creative and budget-friendly DIY home',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                'category_id' => 10,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Top 10 Life-Changing Productivity Hacks" Description ',
                'description' => 'Find out how adopting a minimalist lifestyle can improve your life and bring',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                'category_id' => 11,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'The Ultimate Travel Guide to Bali in 2024" ',
                'description' => 'Follow along with this simple and effective yoga routine designed for complete beginners',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                'category_id' => 12,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'How to Cook the Perfect Steak at Home" ',
                'description' => 'Experience the vibrancy of Japan’s culture by diving into its most',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                'category_id' => 13,
            ],
        ];

        $featuredVideos = [
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Top 10 Life-Changing Productivity Hacks',
                'description' => 'Don’t miss these must-watch movies that have captivated audiences',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'featured' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                'category_id' => 11,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'The German City with NO LAWS',
                'description' => 'Learn how to create a professional-looking website step-by-',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'featured' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                'category_id' => 8,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => '5 Mind-Bending Science Experiments You Can Do ',
                'description' => '',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'featured' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                'category_id' => 7,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Beginner’s Guide to Investing in 2024" ',
                'description' => '',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'featured' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                'category_id' => 6,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'A Day in My Life as a Digital Nomad ',
                'description' => '',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'featured' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                'category_id' => 4,
            ],
            [
                'user_id' => $userId,
                'country_id' => 1,
                'title' => 'Exploring the Deep Sea: Mysteries of the Ocean ',
                'description' => '',
                'created_at' => now()->subHours(mt_rand(1, 170)),
                'published_at' => now()->subHours(mt_rand(1, 170)),
                'duration' => 746,
                'featured' => true,
                'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                'category_id' => 2,
            ],
        ];

        $series = [
            [
                'user_id' => $userId,
                'title' => 'The Ultimate Travel Guide to Bali in ',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'banner' => true,
                'thumbnail_path' => storage_path('/images/series/thumbnail.png'),
                'trailer_thumbnail_path' => storage_path('/images/series/trailer.png'),
                'desktop_banner_path' => storage_path('/images/series/desktop_banner.png'),
                'mobile_banner_path' => storage_path('/images/series/mobile_banner.png'),
//                'trailer' => 'https://rumble.com/embed/v5jm2mz/?pub=3ybkyn',
                'published_at' => now()->subMonth(),
                'category_id' => 1,
                'videos' => [
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 1,
                        'title' => 'How to Cook the Perfect Steak at ',
                        'description' => '',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                        'category_id' => 1,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 2,
                        'title' => '5 Mind-Bending Science Experiments You ',
                        'description' => '',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                        'category_id' => 1,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 3,
                        'title' => 'Beginner’s Guide to Investing in ',
                        'description' => '',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                        'category_id' => 1,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 4,
                        'title' => 'A Day in My Life as a ',
                        'description' => '',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                        'category_id' => 1,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 5,
                        'title' => 'Exploring the Deep Sea: Mysteries of ',
                        'description' => '',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                        'category_id' => 1,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 6,
                        'title' => '10 Tips for Learning a New Language ',
                        'description' => '',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                        'category_id' => 1,
                    ],
                ],
            ],
            [
                'user_id' => $userId,
                'title' => 'Unboxing the Latest iPhone 16 Pro – ',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'banner' => true,
                'thumbnail_path' => storage_path('/images/series/thumbnail.png'),
                'trailer_thumbnail_path' => storage_path('/images/series/trailer.png'),
                'desktop_banner_path' => storage_path('/images/series/desktop_banner.png'),
                'mobile_banner_path' => storage_path('/images/series/mobile_banner.png'),
//                'trailer' => 'https://rumble.com/embed/v5jm2mz/?pub=3ybkyn',
                'published_at' => now()->subMonth(),
                'category_id' => 2,
                'videos' => [
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 1,
                        'title' => 'DIY Home Decor Ideas on a Budget',
                        'description' => 'Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                        'category_id' => 2,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 2,
                        'title' => 'Why You Should Try Minimalism in 2024',
                        'description' => 'Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                        'category_id' => 2,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 3,
                        'title' => 'The Best Yoga Routine for Beginners" ',
                        'description' => 'Master the art of steak-making with this step-by- Master the art of steak-making with this step-by- Master the art of steak-making with this step-by- Master the art of steak-making with this step-by- Master the art of steak-making with this step-by-',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                        'category_id' => 2,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 4,
                        'title' => 'Exploring Japan’s Top 5 Unique ',
                        'description' => 'Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                        'category_id' => 2,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 5,
                        'title' => 'Top 10 Movies to Watch This Year',
                        'description' => 'Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                        'category_id' => 2,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 3,
                        'display_order' => 6,
                        'title' => 'How to Build Your First Website with ',
                        'description' => 'Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel,',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                        'category_id' => 2,
                    ],
                ],
            ],
            [
                'user_id' => $userId,
                'title' => 'Top 10 Life-Changing Productivity Hacks',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'thumbnail_path' => storage_path('/images/series/thumbnail.png'),
                'trailer_thumbnail_path' => storage_path('/images/series/trailer.png'),
                'desktop_banner_path' => storage_path('/images/series/desktop_banner.png'),
                'mobile_banner_path' => storage_path('/images/series/mobile_banner.png'),
//                'trailer' => 'https://rumble.com/embed/v5jm2mz/?pub=3ybkyn',
                'published_at' => now()->subMonth(),
                'category_id' => 3,
                'videos' => [
                    [
                        'user_id' => $userId,
                        'country_id' => 4,
                        'display_order' => 1,
                        'title' => 'The Ultimate Travel Guide to Bali in ',
                        'description' => 'Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                        'category_id' => 3,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 4,
                        'display_order' => 2,
                        'title' => 'How to Cook the Perfect Steak at ',
                        'description' => 'Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                        'category_id' => 3,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 9,
                        'display_order' => 3,
                        'title' => '5 Mind-Bending Science Experiments You ',
                        'description' => 'Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                        'category_id' => 3,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 8,
                        'display_order' => 4,
                        'title' => 'Beginner’s Guide to Investing in ',
                        'description' => 'Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                        'category_id' => 3,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 7,
                        'display_order' => 5,
                        'title' => 'A Day in My Life as a ',
                        'description' => 'Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                        'category_id' => 3,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 5,
                        'display_order' => 6,
                        'title' => 'Exploring the Deep Sea: Mysteries of ',
                        'description' => 'Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                        'category_id' => 3,
                    ],
                ],
            ],
            [
                'user_id' => $userId,
                'title' => '10 Tips for Learning a New Language ',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'thumbnail_path' => storage_path('/images/series/thumbnail.png'),
                'trailer_thumbnail_path' => storage_path('/images/series/trailer.png'),
                'desktop_banner_path' => storage_path('/images/series/desktop_banner.png'),
                'mobile_banner_path' => storage_path('/images/series/mobile_banner.png'),
                'published_at' => now(),
//                'trailer' => 'https://rumble.com/embed/v5jm2mz/?pub=3ybkyn',
                'category_id' => 4,
                'videos' => [
                    [
                        'user_id' => $userId,
                        'country_id' => 11,
                        'display_order' => 1,
                        'title' => 'Unboxing the Latest iPhone 16 Pro – ',
                        'description' => 'Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                        'category_id' => 4,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 11,
                        'display_order' => 2,
                        'title' => 'DIY Home Decor Ideas on a Budget',
                        'description' => 'Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                        'category_id' => 4,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id'=> 11,
                        'display_order' => 3,
                        'title' => 'Why You Should Try Minimalism in 2024',
                        'description' => 'Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by-',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                        'category_id' => 4,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 11,
                        'display_order' => 4,
                        'title' => 'The Best Yoga Routine for Beginners" ',
                        'description' => 'Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work Discover game-changing productivity tips that will transform the way you work',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                        'category_id' => 4,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 14,
                        'display_order' => 5,
                        'title' => 'Exploring Japan’s Top 5 Unique ',
                        'description' => 'Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips Explore the must-visit spots, hidden gems, and travel tips',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                        'category_id' => 4,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 13,
                        'display_order' => 6,
                        'title' => 'Top 10 Movies to Watch This Year',
                        'description' => 'Master the art of steak-making with this step-by- Master the art of steak-making with this step-by- Master the art of steak-making with this step-by- Master the art of steak-making with this step-by- Master the art of steak-making with this step-by-',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                        'category_id' => 4,
                    ],
                ],
            ],
            [
                'user_id' => $userId,
                'title' => 'How to Build Your First Website with ',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'thumbnail_path' => storage_path('/images/series/thumbnail.png'),
                'trailer_thumbnail_path' => storage_path('/images/series/trailer.png'),
                'desktop_banner_path' => storage_path('/images/series/desktop_banner.png'),
                'mobile_banner_path' => storage_path('/images/series/mobile_banner.png'),
                'published_at' => now(),
//                'trailer' => 'https://rumble.com/embed/v5jm2mz/?pub=3ybkyn',
                'category_id' => 5,
                'videos' => [
                    [
                        'user_id' => $userId,
                        'country_id' => 19,
                        'display_order' => 1,
                        'title' => 'I spots, hidden gems,',
                        'description' => 'Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using Experience jaw-dropping experiments that will amaze you and your friends using',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                        'category_id' => 5,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 19,
                        'display_order' => 2,
                        'title' => 'I -making with this step',
                        'description' => 'Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with Learn the basics of investing and start your journey toward financial independence with',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                        'category_id' => 5,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 19,
                        'display_order' => 3,
                        'title' => 'I that will amaze you and',
                        'description' => 'Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel, Follow my routine as a digital nomad, balancing work, travel,',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                        'category_id' => 5,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 19,
                        'display_order' => 4,
                        'title' => 'I and start your journey toward',
                        'description' => 'Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about Dive into the unexplored depths of the ocean and uncover fascinating secrets about',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                        'category_id' => 5,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 32,
                        'display_order' => 5,
                        'title' => 'I digital nomad, balancing work',
                        'description' => 'Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by Accelerate your language learning with proven tips and tricks that polyglots swear by',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                        'category_id' => 5,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 18,
                        'display_order' => 6,
                        'title' => 'I of the ocean and uncover',
                        'description' => 'Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest Check out our unboxing and detailed first look at Apple’s latest',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                        'category_id' => 5,
                    ],
                ],
            ],
            [
                'user_id' => $userId,
                'title' => 'I proven tips and tricks that',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'thumbnail_path' => storage_path('/images/series/thumbnail.png'),
                'trailer_thumbnail_path' => storage_path('/images/series/trailer.png'),
                'desktop_banner_path' => storage_path('/images/series/desktop_banner.png'),
                'mobile_banner_path' => storage_path('/images/series/mobile_banner.png'),
//                'trailer' => 'https://rumble.com/embed/v5jm2mz/?pub=3ybkyn',
                'published_at' => now(),
                'category_id' => 6,
                'videos' => [
                    [
                        'user_id' => $userId,
                        'country_id' => 23,
                        'display_order' => 1,
                        'title' => 'I detailed first look at Apple',
                        'description' => 'Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home Transform your living space with these creative and budget-friendly DIY home',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img1.png'),
                        'category_id' => 6,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 23,
                        'display_order' => 2,
                        'title' => 'I these creative and budget-',
                        'description' => 'Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring Find out how adopting a minimalist lifestyle can improve your life and bring',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img2.png'),
                        'category_id' => 6,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 23,
                        'display_order' => 3,
                        'title' => 'I minimalist lifestyle can improve your',
                        'description' => 'Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners Follow along with this simple and effective yoga routine designed for complete beginners',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img3.png'),
                        'category_id' => 6,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 23,
                        'display_order' => 4,
                        'title' => 'I and effective yoga routine designed',
                        'description' => 'Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most Experience the vibrancy of Japan’s culture by diving into its most',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img4.png'),
                        'category_id' => 6,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 23,
                        'display_order' => 5,
                        'title' => 'I ’s culture by diving',
                        'description' => 'Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences Don’t miss these must-watch movies that have captivated audiences',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img5.png'),
                        'category_id' => 6,
                    ],
                    [
                        'user_id' => $userId,
                        'country_id' => 23,
                        'display_order' => 6,
                        'title' => 'I must-watch movies that',
                        'description' => 'Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by- Learn how to create a professional-looking website step-by-',
                        'created_at' => now()->subHours(mt_rand(1, 170)),
                        'published_at' => now()->subHours(mt_rand(1, 170)),
                        'duration' => 746,
                        'thumbnail_path' => storage_path('/images/videos/long/img6.png'),
                        'category_id' => 6,
                    ],
                ],
            ],
        ];

        foreach ($videos as $video) {
            $videoModel = Video::create(Arr::except($video, ['thumbnail_path', 'category_id']));

            $videoModel->addMedia($video['thumbnail_path'])
                ->preservingOriginal()
                ->toMediaCollection('thumbnail');

            if (isset($video['category_id'])) {
               VideoCategory::create(['category_id' => $video['category_id'], 'video_id' => $videoModel->id]);
            }

            $numberOfComments = mt_rand(1, 5);
            for ($i = 0; $i < $numberOfComments; $i++) {
                Comment::create([
                    'video_id' => $videoModel->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'content' => 'This is a sample comment for video ID ' . $videoModel->id,
                ]);
            }
        }

        foreach ($featuredVideos as $video) {
            $videoModel = Video::create(Arr::except($video, ['thumbnail_path', 'category_id']));

            $videoModel->addMedia($video['thumbnail_path'])
                ->preservingOriginal()
                ->toMediaCollection('thumbnail');

            if (isset($video['category_id'])) {
                $videoModel->categories()->attach($video['category_id']);
            }
        }


        foreach ($series as $item) {
            $seriesModel = Series::create(Arr::except($item, ['thumbnail_path', 'trailer_thumbnail_path', 'desktop_banner_path', 'mobile_banner_path', 'videos', 'category_id','trailer']));

            $seriesModel->addMedia($item['thumbnail_path'])
                ->preservingOriginal()
                ->toMediaCollection('thumbnail');

            $seriesModel->addMedia($item['trailer_thumbnail_path'])
                ->preservingOriginal()
                ->toMediaCollection('trailer_thumbnail');

            $seriesModel->addMedia($item['desktop_banner_path'])
                ->preservingOriginal()
                ->toMediaCollection('desktop_banner');

            $seriesModel->addMedia($item['mobile_banner_path'])
                ->preservingOriginal()
                ->toMediaCollection('mobile_banner');

            $categoryId = $item['category_id'];
            $seriesModel->categories()->attach($categoryId);
            $x = 0;
            foreach ($item['videos'] as $seriesVideo) {

                    $video = $seriesModel->videos()->create(Arr::except($seriesVideo, ['thumbnail_path', 'category_id']));




                VideoCategory::create(['category_id' => $categoryId, 'video_id' => $video->id]);

                $video->addMedia($seriesVideo['thumbnail_path'])
                    ->preservingOriginal()
                    ->toMediaCollection('thumbnail');

                $categoryId = $seriesVideo['category_id'];
                $video->categories()->attach($categoryId);
            }

        }
    }
}
