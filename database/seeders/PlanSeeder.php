<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate([
            'copecart_id' => '9d2de4b1',
        ], [
            'name' => 'yearly',
            'title' => 'Unlock a Year of Taboo Streaming — And Save Big!',
            'apple_id' => 'yearly_subscription',
            'price' => 19999,
            'save_percentage' => 20,
            'trial_days' => 3,
            'checkout_url' => 'https://copecart.com/us/products/9d2de4b1/checkout',
            'features' => [
                'enjoy short form, long form, & exclusive series',
                'full-access to all premium content/creators',
                'engage in live-chat with other users',
                'exclusive infinity badge next to all your comments',
                'exclusive badge for early-adopters who subscribe in 2024 (coming soon)',
                'access to community posts (coming soon)',
                "available on Android & Apple TV's (coming early 2025)",
            ]
        ]);

        Plan::updateOrCreate([
            'copecart_id' => 'b128a528',
        ], [
            'name' => 'monthly',
            'title' => 'Your Monthly Pass to Unfiltered Entertainment',
            'apple_id' => 'monthly_subscription',
            'price' => 1999,
            'save_percentage' => 0,
            'trial_days' => 3,
            'checkout_url' => 'https://copecart.com/us/products/b128a528/checkout',
            'features' => [
                'enjoy short form, long form, & exclusive series',
                'full-access to all premium content/creators',
                'engage in live-chat with other users',
                'exclusive infinity badge next to all your comments',
                'exclusive badge for early-adopters who subscribe in 2024 (coming soon)',
                'access to community posts (coming soon)',
                "available on Android & Apple TV's (coming early 2025)",
            ]
        ]);

        Plan::updateOrCreate([
            'copecart_id' => '7780cdb5',
        ], [
            'name' => 'lifetime',
            'apple_id' => 'tabootv_lifetime_subscription',
            'checkout_url' => 'https://copecart.com/us/products/7780cdb5/checkout',
            'description' => 'One payment, endless entertainment. Lifetime streaming awaits.',
            'price' => 29999,
            'features' => [
                'Access to all features',
                'Exclusive bonus zoom calls with Arab',
            ]
        ]);
    }
}
