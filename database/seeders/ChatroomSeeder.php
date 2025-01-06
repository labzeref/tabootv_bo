<?php

namespace Database\Seeders;

use App\Enums\ChatroomPrivacyEnum;
use App\Models\Chatroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chatroom::create([
            'privacy' => ChatroomPrivacyEnum::public,
            'name' => 'Global',
        ]);
    }
}
