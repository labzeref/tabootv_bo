<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Models\Channel;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'country_id' => Country::where('iso', 'SAU')->value('id') ?? Country::inRandomOrder()->value('id'),
            'first_name' => 'Arab',
            'last_name' => 'Uncut',
            'display_name' => 'Arabuncut',
            'gender' => GenderEnum::male,
            'email' => 'arab@taboo.tv',
            'password' => Hash::make('password'),
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);
    }
}
