<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::create([
            'chatroom_id' => 1, // Assuming a chatroom with ID 1 exists
            'user_id' => 1, // Assuming a user with ID 1 exists
            'content' => 'This is a sample message.',
        ]);
    }
}
